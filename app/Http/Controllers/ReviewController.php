<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Добавление отзыва к товару
    public function store(StoreReviewRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['product_id'] = $product->id;

            $review = Review::create($data);

            return back()->with('success', 'Отзыв успешно добавлен');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при добавлении отзыва. Пожалуйста, попробуйте снова.');
        }
    }

    public function destroy(Review $review)
    {
        try {
            if ($review->user_id !== Auth::id()) {
                abort(403);
            }

            $review->delete();

            return back()->with('success', 'Отзыв успешно удален');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при удалении отзыва. Пожалуйста, попробуйте снова.');
        }
    }
}