<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;
class HomeController extends Controller
{
     public function index()
    {
        $services = Service::active()->get();
       $products = Product::where('status', 'active')
            ->take(8) // Limit for homepage
            ->get();
        return view('home.index', compact('services', 'products'));
    }
}
