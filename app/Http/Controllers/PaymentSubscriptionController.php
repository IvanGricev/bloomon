<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentSubscriptionController extends Controller
{
    /**
     * Отображает форму оплаты подписки.
     * Ожидается параметр subscription_id в query string.
     */
    public function showPaymentForm(Request $request)
    {
        $subscriptionId = $request->query('subscription_id');
        return view('subscriptions.subscription_payment', compact('subscriptionId'));
    }

    /**
     * Обрабатывает оплату подписки.
     */
    public function processPayment(Request $request)
    {
        $data = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'card_number' => 'required|string',
            'expiry_date' => 'required|string',
            'cvv' => 'required|string',
        ]);

        // Здесь можно подключить платёжный шлюз.
        // Для демонстрации просто считаем, что оплата прошла успешно.
        
        return redirect()->route('profile.subscriptions')->with('success', 'Оплата подписки прошла успешно!');
    }
}