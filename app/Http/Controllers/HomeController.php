<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Subscription;
use App\Models\BlogPost;

class HomeController extends Controller
{
    /**
     * Отображает главную страницу сайта.
     */
    public function index()
    {
        $promotions = Promotion::active()->get(); // Теперь метод active() определён в модели
        $subscriptions = Subscription::limit(3)->get();
        $blogPosts = BlogPost::latest()->limit(3)->get();
        return view('home', compact('promotions', 'subscriptions', 'blogPosts'));
    }
}