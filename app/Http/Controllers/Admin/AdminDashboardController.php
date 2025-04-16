<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $usersCount = User::count();
        $promotions = Promotion::all();

        return view('admin.index', compact('ordersCount', 'productsCount', 'usersCount', 'promotions'));
    }
}