<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Category;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('categories')->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        // Получаем все категории для множественного выбора
        $categories = Category::all();
        return view('admin.promotions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'discount'     => 'required|numeric|min:0|max:100',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            // Принимаем массив category_ids изменяя имя поля в форме
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        try {
            $promotion = Promotion::create([
                'name' => $validated['name'],
                'description'  => $validated['description'],
                'discount'     => $validated['discount'],
                'start_date'   => $validated['start_date'],
                'end_date'     => $validated['end_date'],
            ]);

            // Привязываем выбранные категории
            $promotion->categories()->sync($validated['category_ids']);

            return redirect()->route('admin.promotions.index')
                             ->with('success', 'Акция успешно создана.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при создании акции: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function edit($id)
    {
        $promotion = Promotion::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('admin.promotions.edit', compact('promotion', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'discount'     => 'required|numeric|min:0|max:100',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        try {
            $promotion->update([
                'name' => $validated['name'],
                'description'  => $validated['description'],
                'discount'     => $validated['discount'],
                'start_date'   => $validated['start_date'],
                'end_date'     => $validated['end_date'],
            ]);

            $promotion->categories()->sync($validated['category_ids']);

            return redirect()->route('admin.promotions.index')
                             ->with('success', 'Акция успешно обновлена.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при обновлении акции: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();
    
            return redirect()->route('admin.promotions.index')
                             ->with('success', 'Акция успешно удалена.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при удалении акции: ' . $e->getMessage());
        }
    }
}