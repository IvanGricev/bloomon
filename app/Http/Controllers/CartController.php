<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Отображение содержимого корзины.
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Добавление товара в корзину.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     */
    public function add(Request $request, $productId)
    {
        // Получаем товар или выбрасываем 404, если не найден.
        $product = Product::findOrFail($productId);
        // Значение количества (по умолчанию 1)
        $quantity = $request->input('quantity', 1);
        
        $cart = session()->get('cart', []);
        // Если товар уже есть в корзине, увеличиваем количество.
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'photo'    => $product->photo,
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину.');
    }

    /**
     * Обновление количества товаров в корзине.
     */
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $quantities = $request->input('quantities', []);
        foreach ($quantities as $productId => $quantity) {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
            }
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Корзина обновлена.');
    }

    /**
     * Удаление товара из корзины.
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Товар удалён из корзины.');
    }

    /**
     * Вывод страницы оформления заказа (checkout).
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста.');
        }
        // Расчёт общей суммы
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        return view('cart.checkout', compact('cart', 'totalPrice'));
    }
}