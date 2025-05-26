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

        // Поиск по названию или категории
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Фильтрация по категориям
        if ($request->has('categories')) {
            $query->whereIn('category_id', $request->get('categories'));
        }

        $products = $query->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // Отображение карточки конкретного товара
    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);
        return view('products.show', compact('product'));
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
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('products', 'public');
                $data['photo'] = $path;
            }

            $product = Product::create($data);

            return redirect()->route('products.show', $product)
                ->with('success', 'Товар успешно создан');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при создании товара. Пожалуйста, попробуйте снова.');
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
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                if ($product->photo) {
                    Storage::disk('public')->delete($product->photo);
                }
                $path = $request->file('photo')->store('products', 'public');
                $data['photo'] = $path;
            }

            $product->update($data);

            return redirect()->route('products.show', $product)
                ->with('success', 'Товар успешно обновлен');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при обновлении товара. Пожалуйста, попробуйте снова.');
        }
    }

    // Удаление товара
    public function destroy(Product $product)
    {
        try {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Товар успешно удален');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при удалении товара. Пожалуйста, попробуйте снова.');
        }
    }
}