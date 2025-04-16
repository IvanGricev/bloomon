<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Пример аналитики для дашборда
        $ordersCount   = Order::count();
        $productsCount = Product::count();
        $usersCount    = User::count();
        $promotions    = Promotion::all();
        return view('admin.dashboard', compact('ordersCount', 'productsCount', 'usersCount', 'promotions'));
    }
}