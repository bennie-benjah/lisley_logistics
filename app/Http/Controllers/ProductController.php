<?php
// App\Http\Controllers\ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('home.products', compact('products'));
    }
    
    // If you want products on homepage too
    public function getHomeProducts()
    {
        $products = Product::where('status', 'active')
            ->take(8)
            ->get();
            
        return $products;
    }
}