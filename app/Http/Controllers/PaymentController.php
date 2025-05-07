<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Отображение формы оплаты картой.
     */
    public function showCardPaymentForm(Request $request)
    {
        // Передаем order_id через query string, например: /card-payment?order_id=123
        return view('cart.card_payment');
    }

    /**
     * Обработка данных оплаты картой.
     */
    public function processCardPayment(Request $request)
    {
        $data = $request->validate([
            'order_id'    => 'required|integer|exists:orders,id',
            'card_number' => 'required|string',
            'expiry_date' => 'required|string',
            'cvv'         => 'required|string',
        ]);

        // Здесь можно добавить интеграцию с платежным провайдером.
        // Для симуляции считаем платёж успешным и обновляем статус заказа на "paid".
        $order = Order::findOrFail($data['order_id']);
        $order->update(['status' => 'paid']);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Оплата прошла успешно!');
    }
}