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
        // Получаем корзину из сессии
        $cart = session()->get('cart', []);

        // Получаем активные акции с их категориями
        $activePromotions = \App\Models\Promotion::active()->with('categories')->get();

        // Проходимся по товарам корзины и переопределяем цены, если акция активна
        foreach ($cart as &$item) {
            $product = Product::find($item['id']);
            
            // Проверяем доступность товара
            if (!$product->isAvailable($item['quantity'])) {
                // Если товар больше недоступен, удаляем его из корзины
                unset($cart[$item['id']]);
                continue;
            }
            
            // Устанавливаем исходную цену товара из базы
            $item['original_price'] = $product->price;
            $item['applied_promotion'] = null;
            $item['available_quantity'] = $product->quantity; // Добавляем информацию о доступном количестве

            foreach ($activePromotions as $promo) {
                if ($promo->categories->contains($product->category_id)) {
                    $item['applied_promotion'] = $promo->name;
                    $item['discount'] = $promo->discount; // в процентах
                    $item['discounted_price'] = $product->price * ((100 - $promo->discount) / 100);
                    break; // Если найдено, выходим из цикла
                }
            }

            // Если акция не применена – итоговая цена равна исходной
            if (!isset($item['discounted_price'])) {
                $item['discounted_price'] = $product->price;
            }
        }
        unset($item);

        // Сохраняем обновленную корзину
        session()->put('cart', $cart);

        return view('cart.index', compact('cart'));
    }    

    /**
     * Добавление товара в корзину.
     */
    public function add(Request $request, $productId)
    {
        // Получаем товар или выбрасываем 404, если не найден
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        // Проверяем доступность товара
        if (!$product->isAvailable($quantity)) {
            return redirect()->back()->with('error', 'Запрошенное количество товара недоступно.');
        }

        $cart = session()->get('cart', []);
        
        // Если товар уже есть в корзине, проверяем общее количество
        if (isset($cart[$productId])) {
            $totalQuantity = $cart[$productId]['quantity'] + $quantity;
            if (!$product->isAvailable($totalQuantity)) {
                return redirect()->back()->with('error', 'Общее количество товара в корзине превышает доступное.');
            }
            $cart[$productId]['quantity'] = $totalQuantity;
        } else {
            $cart[$productId] = [
                'id'          => $product->id,
                'name'        => $product->name,
                'price'       => $product->price,
                'quantity'    => $quantity,
                'photo'       => $product->photo,
                'category_id' => $product->category_id,
                'available_quantity' => $product->quantity,
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
        $productId = $request->input('productId');
        $action = $request->input('action');
    
        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            
            if ($action === 'increment') {
                // Проверяем доступность перед увеличением
                if (!$product->isAvailable($cart[$productId]['quantity'] + 1)) {
                    return redirect()->back()->with('error', 'Запрошенное количество товара недоступно.');
                }
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrement') {
                $cart[$productId]['quantity']--;
                // Если количество меньше 1, убираем товар из корзины
                if ($cart[$productId]['quantity'] < 1) {
                    unset($cart[$productId]);
                }
            }
            
            // Обновляем информацию о доступном количестве
            if (isset($cart[$productId])) {
                $cart[$productId]['available_quantity'] = $product->quantity;
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
     * Вывод страницы оформления заказа (checkout) с перерасчётом скидок.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста.');
        }
        
        $activePromotions = \App\Models\Promotion::active()->with('categories')->get();
        $totalOriginal = 0;
        $totalDiscounted = 0;
        
        foreach ($cart as &$item) {
            // Получаем обновлённые данные товара
            $product = Product::find($item['id']);
            
            // Проверяем доступность товара
            if (!$product->isAvailable($item['quantity'])) {
                return redirect()->route('cart.index')
                    ->with('error', "Товар '{$product->name}' больше недоступен в запрошенном количестве.");
            }
            
            $item['original_price'] = $product->price;
            $item['applied_promotion'] = null;
            $item['available_quantity'] = $product->quantity;
            
            foreach ($activePromotions as $promo) {
                if (isset($item['category_id']) && $promo->categories->contains($item['category_id'])) {
                    $item['applied_promotion'] = $promo->name;
                    $item['discount'] = $promo->discount;
                    $item['discounted_price'] = $product->price * ((100 - $promo->discount) / 100);
                    break;
                }
            }
            if (!isset($item['discounted_price'])) {
                $item['discounted_price'] = $product->price;
            }
            
            $totalOriginal += $product->price * $item['quantity'];
            $totalDiscounted += $item['discounted_price'] * $item['quantity'];
        }
        unset($item);
        
        return view('cart.checkout', compact('cart', 'totalOriginal', 'totalDiscounted'));
    }
}