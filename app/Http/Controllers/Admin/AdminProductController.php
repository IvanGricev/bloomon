<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
    {
         $products = Product::all();
         return view('admin.products.index', compact('products'));
    }

    public function create()
    {
         return view('admin.products.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
              'name'        => 'required|string',
              'description' => 'nullable|string',
              'price'       => 'required|numeric',
              'category_id' => 'required|exists:categories,id',
              'photo'       => 'nullable|string',
         ]);
         Product::create($validated);
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