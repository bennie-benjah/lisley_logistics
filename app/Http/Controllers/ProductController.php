<?php
// App\Http\Controllers\ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
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
    public function products()
{
    $categories = Category::all();
    $products = Product::all();

    // Map products and categories to plain arrays for JS
    $categoriesArray = $categories->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => $c->slug
        ];
    });

    $productsArray = $products->map(function($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'description' => $p->description,
            'price' => (float) $p->price,
            'stock_quantity' => $p->stock_quantity,
            'category' => $p->category_slug,
            'image' => $p->image_url
        ];
    });

    return view('home.products', compact('categoriesArray', 'productsArray'));
}
}
