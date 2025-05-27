<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::withTrashed()
            ->with(['orders', 'subscriptions', 'supportTickets', 'reviews.product'])
            ->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Данные пользователя успешно обновлены');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно восстановлен');
    }

    // Обновление статуса заказа
    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);
        return response()->json(['success' => true, 'message' => 'Статус заказа обновлен']);
    }

    // Обновление статуса подписки
    public function updateSubscriptionStatus(Request $request, $userId, $subscriptionId)
    {
        $user = User::findOrFail($userId);
        $validated = $request->validate([
            'status' => 'required|in:active,paused,cancelled'
        ]);

        $user->subscriptions()->updateExistingPivot($subscriptionId, [
            'status' => $validated['status']
        ]);

        return redirect()->back()->with('success', 'Статус подписки успешно обновлен');
    }

    // Обновление отзыва
    public function updateReview(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        $review->update($validated);
        return response()->json(['success' => true, 'message' => 'Отзыв обновлен']);
    }

    // Удаление отзыва
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();
        return response()->json(['success' => true, 'message' => 'Отзыв удален']);
    }
}