<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Публичный список подписок для всех пользователей.
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Страница подписок в профиле пользователя:
     * выводит все подписки и подписки конкретного пользователя.
     */
    public function profileIndex()
    {
        $allSubscriptions = Subscription::all();
        $userSubscriptions = Auth::user()->subscriptions()->withPivot(['last_payment_date', 'subscription_end_date', 'status'])->get();
        return view('profile.subscriptions', compact('allSubscriptions', 'userSubscriptions'));
    }

    /**
     * Показывает форму оплаты подписки.
     */
    public function showPaymentForm($id)
    {
        $subscription = Subscription::findOrFail($id);
        return view('subscriptions.payment', compact('subscription'));
    }

    /**
     * Обрабатывает оплату и оформляет подписку.
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'card_number' => 'required|string|size:16',
            'expiry_date' => 'required|string|size:5',
            'cvv' => 'required|string|size:3',
        ]);

        $user = Auth::user();
        $subscription = Subscription::findOrFail($id);

        // Здесь должна быть реальная интеграция с платежной системой
        // Для примера просто проверяем валидность данных карты
        if (!$this->validateCard($request->card_number, $request->expiry_date, $request->cvv)) {
            return redirect()->back()->with('error', 'Ошибка при обработке платежа. Проверьте данные карты.');
        }

        // Если оплата прошла успешно, оформляем подписку
        if (!$user->subscriptions->contains($id)) {
            $now = Carbon::now();
            $endDate = $subscription->calculateEndDate($now);

            $user->subscriptions()->attach($id, [
                'last_payment_date' => $now,
                'subscription_end_date' => $endDate,
                'status' => 'active'
            ]);

            return redirect()->route('profile.subscriptions')->with('success', 'Оплата прошла успешно! Вы подписаны.');
        }

        return redirect()->back()->with('error', 'Вы уже подписаны на эту подписку.');
    }

    /**
     * Отписка пользователя от подписки.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->subscriptions->contains($id)) {
            // Полностью удаляем подписку из базы данных
            $user->subscriptions()->detach($id);
            return redirect()->route('profile.subscriptions')->with('success', 'Вы успешно отписались.');
        }
        return redirect()->back()->with('error', 'Вы не подписаны на эту подписку.');
    }

    /**
     * Продление подписки.
     */
    public function renew($id)
    {
        $user = Auth::user();
        $subscription = Subscription::findOrFail($id);

        if ($user->subscriptions->contains($id)) {
            $pivot = $user->subscriptions()->where('subscription_id', $id)->first()->pivot;
            
            // Проверяем, что подписка активна или истекает в течение недели
            if ($pivot->status !== 'active' && !$pivot->isExpiringSoon()) {
                return redirect()->back()->with('error', 'Невозможно продлить неактивную подписку.');
            }

            // Перенаправляем на страницу оплаты для продления
            return redirect()->route('subscriptions.payment', $id);
        }

        return redirect()->back()->with('error', 'Вы не подписаны на эту подписку.');
    }

    /**
     * Валидация данных карты (для примера).
     */
    private function validateCard($cardNumber, $expiryDate, $cvv)
    {
        // Здесь должна быть реальная валидация карты
        // Для примера просто проверяем формат
        return preg_match('/^\d{16}$/', $cardNumber) &&
               preg_match('/^\d{2}\/\d{2}$/', $expiryDate) &&
               preg_match('/^\d{3}$/', $cvv);
    }

    /**
     * Оформление подписки (перенаправление на страницу оплаты).
     */
    public function store($id)
    {
        $subscription = Subscription::findOrFail($id);
        return redirect()->route('subscriptions.payment', $id);
    }
}