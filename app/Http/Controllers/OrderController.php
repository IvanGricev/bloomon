<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\DeliveryTimeService;

class OrderController extends Controller
{
    protected $deliveryTimeService;

    public function __construct(DeliveryTimeService $deliveryTimeService)
    {
        $this->deliveryTimeService = $deliveryTimeService;
    }
    
    public function getTimeSlots(Request $request)
    {
        $date = $request->input('date');
        $slots = $this->deliveryTimeService->getAvailableTimeSlots($date);
        return response()->json(['slots' => $slots]);
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
    
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['status'] = 'pending';

            $order = Order::create($data);

            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ успешно создан');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Произошла ошибка при создании заказа. Пожалуйста, попробуйте снова.');
        }
    }
}