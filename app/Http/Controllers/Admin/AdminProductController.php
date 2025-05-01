<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminProductController extends Controller
{
    public function index()
    {
         $products = Product::all();
         return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
    
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'required|image|max:2048', // максимальный размер 2 Мб, можно настроить
        ]);

        if ($request->hasFile('photo')) {
            // Сохраним изображение в папку 'products' в диске 'public'
            $path = $request->file('photo')->store('products', 'public');
            // $path будет содержать путь вида "products/filename.jpg"
        } else {
            $path = null;
        }

        // Создаем товар, включая путь к изображению
        $product = Product::create([
            'name'        => $validated['name'],
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => $validated['category_id'],
            'photo'       => $path, // сохраняем путь в базе
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Товар создан.');
    }
    public function edit($id)
    {
         $product = Product::findOrFail($id);
         return view('admin.products.edit', compact('product'));
    }
    
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
         return redirect()->route('admin.products.index')->with('success', 'Товар обновлён.');
    }

    public function destroy($id)
    {
         $product = Product::findOrFail($id);
         $product->delete();
         return redirect()->route('admin.products.index')->with('success', 'Товар удалён.');
    }
}