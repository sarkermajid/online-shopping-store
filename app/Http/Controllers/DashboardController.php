<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalBrands = Brand::count();
        $totalBlogs = Blog::count();
        $totalMessages = Contact::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalPromos = PromoCode::count();

        return view('admin.home.index', compact(
            'totalCategories',
            'totalUsers',
            'totalBrands',
            'totalBlogs',
            'totalMessages',
            'totalProducts',
            'totalOrders',
            'totalPromos'
        ));
    }
}
