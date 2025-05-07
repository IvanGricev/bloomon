<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Отображение формы оплаты картой с разбивкой итоговой суммы заказа.
     *
     * Здесь мы получаем ID заказа через query string (например, /card-payment?order_id=123),
     * загружаем заказ с его позициями, затем для каждой позиции запрашиваем исходную цену продукта
     * (через связь orderItems.product) и вычисляем:
     *   - Сумму до скидки (исходная цена * количество)
     *   - Итоговую сумму (цена, сохранённая в заказе, * количество)
     *   - Разницу как величину скидки.
     * Итоговые значения передаются в шаблон.
     */
    public function showCardPaymentForm(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        $originalTotal = 0;
        $discountedTotal = 0;
        
        foreach ($order->orderItems as $item) {
            $originalPrice = $item->product->price;
            $originalTotal += $originalPrice * $item->quantity;
            $discountedTotal += $item->price * $item->quantity;
        }
        $discountTotal = $originalTotal - $discountedTotal;

        return view('cart.card_payment', compact(
            'order',
            'originalTotal',
            'discountTotal',
            'discountedTotal'
        ));
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

        $order = Order::findOrFail($data['order_id']);
        $order->update(['status' => 'paid']);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Оплата прошла успешно!');
    }
}