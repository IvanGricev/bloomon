<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Отображение каталога товаров
    public function index(Request $request)
    {
        $query = Product::query();

        // Поиск по названию
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Фильтр по категориям
        if ($request->has('categories')) {
            $query->whereIn('category_id', $request->get('categories'));
        }

        // Фильтр по наличию
        if ($request->has('in_stock')) {
            $query->where('quantity', '>', 0);
        }

        // Получаем товары с пагинацией
        $products = $query->with(['images', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(18);

        // Получаем все категории для фильтров
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // Отображение карточки конкретного товара
    public function show(Product $product)
    {
        try {
            // Загружаем все необходимые связи
            $product->load(['images', 'category', 'reviews.user']);
            
            // Проверяем актуальность данных о товаре
            $product->refresh();
            
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Товар не найден');
        }
    }

    // --- Административные методы (требуют проверки прав) ---

    // Форма создания товара
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Сохранение нового товара
    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            return redirect()->route('products.show', $product)
                ->with('success', 'Товар успешно создан');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании товара: ' . $e->getMessage());
        }
    }

    // Форма редактирования товара
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Обновление товара
    public function update(StoreProductRequest $request, Product $product)
    {
        try {
            $product->update($request->validated());

            if ($request->hasFile('images')) {
                // Удаляем старые изображения
                foreach ($product->images as $image) {
                    \Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }

                // Загружаем новые
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            return redirect()->route('products.show', $product)
                ->with('success', 'Товар успешно обновлен');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при обновлении товара: ' . $e->getMessage());
        }
    }

    // Удаление товара
    public function destroy(Product $product)
    {
        try {
            // Удаляем изображения
            foreach ($product->images as $image) {
                \Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Товар успешно удален');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при удалении товара: ' . $e->getMessage());
        }
    }
}