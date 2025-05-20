<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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
    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // --- Административные методы (требуют проверки прав) ---

    // Форма создания товара
    public function create()
    {
        return view('products.create');
    }

    // Сохранение нового товара
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            // Для загрузки нескольких изображений
            'images.*'    => 'nullable|image|max:2048',
        ]);

        $product = Product::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
        ]);

        // Если файлы загружены, сохраняем каждый и создаем запись в связанной таблице
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                // Предполагаем, что в модели Product создан метод images() (отношение hasMany)
                $product->images()->create(['image_path' => $filename]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Товар успешно создан!');
    }

    // Форма редактирования товара
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Обновление товара
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'photo'       => 'nullable|string',
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Товар обновлён.');
    }

    // Удаление товара
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Товар удалён.');
    }
}