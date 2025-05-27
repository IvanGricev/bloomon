<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminReviewController extends Controller
{
    /**
     * Отображение списка всех отзывов
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Отображение деталей отзыва
     */
    public function show($id)
    {
        $review = Review::with(['user', 'product'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Обновление отзыва
     */
    public function update(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);
            
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string'
            ]);

            $review->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Отзыв успешно обновлен',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении отзыва: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удаление отзыва
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Отзыв успешно удален'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении отзыва: ' . $e->getMessage()
            ], 500);
        }
    }
} 