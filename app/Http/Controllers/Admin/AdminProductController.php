<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class AdminProductController extends Controller
{
    /**
     * Отображение списка товаров.
     */
    public function index()
    {
        // Загружаем товары вместе с изображениями
        $products = Product::with('images')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Отображение формы создания нового товара.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Сохранение нового товара с возможностью загрузки нескольких изображений.
     */
    public function store(Request $request)
    {
        // Валидация входных данных:
        $validated = $request->validate([
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'category_id'   => 'required|exists:categories,id',
            'quantity'      => 'required|integer|min:0',
            'images'        => 'required', // Обязательное поле
            'images.*'      => 'image|max:2048', // Каждое изображение не больше 2 Мб
        ]);

        // Создаем товар без привязки к изображениям
        $product = Product::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? "",
            'price'         => $validated['price'],
            'category_id'   => $validated['category_id'],
            'quantity'      => $validated['quantity'],
        ]);

        // Обработка множественной загрузки изображений:
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Формируем уникальное имя файла
                $filename = time() . '_' . $file->getClientOriginalName();
                // Перемещаем файл в папку public/uploads/products
                $file->move(public_path('uploads/products'), $filename);
                // Создаем запись в связанном отношении (предполагается, что в модели Product есть метод images())
                $product->images()->create(['image_path' => $filename]);
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Товар создан.');
    }

    /**
     * Отображение формы редактирования товара с возможностью добавления новых изображений
     * и управления уже загруженными.
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Обновление товара с возможностью добавления новых изображений.
     */
    public function update(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Валидация входных данных:
        $validated = $request->validate([
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'category_id'   => 'required|exists:categories,id',
            'quantity'      => 'required|integer|min:0',
            // Поле для новых изображений (не обязательно)
            'new_images'    => 'nullable',
            'new_images.*'  => 'image|max:2048',
        ]);

        // Обновляем основные данные товара
        $product->update([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? "",
            'price'         => $validated['price'],
            'category_id'   => $validated['category_id'],
            'quantity'      => $validated['quantity'],
        ]);

        // Если загружены новые изображения, сохраняем их:
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $product->images()->create(['image_path' => $filename]);
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Товар обновлён.');
    }

    /**
     * Удаление товара вместе с его изображениями.
     */
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Удаляем физические файлы изображений
        foreach ($product->images as $image) {
            $filePath = public_path('uploads/products/' . $image->image_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Удаляем товар (если настроен каскад, связанные записи также удалятся)
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Товар удалён.');
    }

    /**
     * Удаление отдельного изображения товара.
     *
     * @param  \App\Models\Product  $product
     * @param  int $imageId
     */
    public function destroyImage(Product $product, $imageId)
    {
        $image = $product->images()->findOrFail($imageId);
        $filePath = public_path('uploads/products/' . $image->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $image->delete();

        return redirect()->back()->with('success', 'Изображение удалено.');
    }

    /**
     * Обновление количества товара.
     */
    public function updateQuantity(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'quantity' => $validated['quantity']
        ]);

        return redirect()->back()->with('success', 'Количество товара обновлено.');
    }

    /**
     * Быстрое обновление количества товара через AJAX.
     */
    public function quickUpdateQuantity(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'action' => 'required|in:set,add',
        ]);

        if ($validated['action'] === 'add') {
            $product->quantity += $validated['quantity'];
        } else {
            $product->quantity = $validated['quantity'];
        }

        $product->save();

        return response()->json([
            'success' => true,
            'new_quantity' => $product->quantity,
            'message' => 'Количество товара обновлено'
        ]);
    }
}