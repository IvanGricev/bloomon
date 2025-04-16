<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Отображение каталога товаров
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Отображение карточки конкретного товара
    public function show($id)
    {
        $product = Product::findOrFail($id);
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
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'photo'       => 'nullable|string'
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Товар создан.');
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