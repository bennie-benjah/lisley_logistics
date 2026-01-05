<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::role('user')->count();
        $totalServices = Service::count();
        $products = Product::with('category')->get();
        $categories = Category::all();
        $customers = User::role('user')->get();
        $activeServices = Service::where('status', 'active')->count();

        return view('admin.index', compact('totalUsers', 'totalServices', 'activeServices', 'products', 'categories', 'customers'));
    }

  
}
