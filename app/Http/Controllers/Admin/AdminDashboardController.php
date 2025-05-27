<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Subscription;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $ordersCount       = Order::count();
        $productsCount     = Product::count();
        $usersCount        = User::count();
        $promotions        = Promotion::with('categories')->get();
        $subscriptionsCount = Subscription::count();

        return view('admin.index', compact(
            'ordersCount',
            'productsCount',
            'usersCount',
            'promotions',
            'subscriptionsCount'
        ));
    }
}