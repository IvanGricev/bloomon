<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flower;
use App\Models\BouquetTemplate;

class BouquetBuilderController extends Controller
{
    // Отображение страницы конструктора букета
    public function index()
    {
        $flowers   = Flower::all();
        $templates = BouquetTemplate::where('is_active', true)->get();
        return view('bouquet-builder.index', compact('flowers', 'templates'));
    }
    
    // Сохранение (или оформление) кастомного букета
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flowers'     => 'required|array',  // Например, id выбранных цветов и их количества
            'total_price' => 'required|numeric'
        ]);
        
        // Логика сохранения заказа на кастомный букет или сохранения дизайна букета
        // Например, можно создать новую модель CustomBouquet и сохранить туда данные
        // CustomBouquet::create([...]);
        
        return redirect()->back()->with('success', 'Букет успешно создан.');
    }
}