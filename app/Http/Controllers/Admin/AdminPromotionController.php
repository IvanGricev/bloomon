<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class AdminPromotionController extends Controller
{
    public function index()
    {
         $promotions = Promotion::all();
         return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
         return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
            'name'       => 'required|string',
            'discount'   => 'required|numeric',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
         ]);
         Promotion::create($validated);
         return redirect()->route('admin.promotions.index')->with('success', 'Акция создана.');
    }

    public function edit($id)
    {
         $promotion = Promotion::findOrFail($id);
         return view('admin.promotions.edit', compact('promotion'));
    }
    
    public function update(Request $request, $id)
    {
         $promotion = Promotion::findOrFail($id);
         $validated = $request->validate([
            'name'       => 'required|string',
            'discount'   => 'required|numeric',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
         ]);
         $promotion->update($validated);
         return redirect()->route('admin.promotions.index')->with('success', 'Акция обновлена.');
    }
    
    public function destroy($id)
    {
         $promotion = Promotion::findOrFail($id);
         $promotion->delete();
         return redirect()->route('admin.promotions.index')->with('success', 'Акция удалена.');
    }
}