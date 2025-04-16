<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Добавление отзыва к товару
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'text'   => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);
        
        $product = Product::findOrFail($productId);
        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'text'       => $validated['text'],
            'rating'     => $validated['rating'],
        ]);
        
        return redirect()->back()->with('success', 'Отзыв добавлен.');
    }
}