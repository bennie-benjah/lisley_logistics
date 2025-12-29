<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        
        $query = Product::with('category')->active();
        
        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by price range
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_50':
                    $query->where('price', '<', 50);
                    break;
                case '50_200':
                    $query->whereBetween('price', [50, 200]);
                    break;
                case 'over_200':
                    $query->where('price', '>', 200);
                    break;
            }
        }
        
        // Filter by search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        $products = $query->paginate(12);
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
        // Increment view count if needed
        // $product->increment('views');
        
        return view('products.show', compact('product'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        
        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image_url,
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')
                         ->with('success', 'Product added to cart!');
    }

    /**
     * Show cart page
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')
                         ->with('success', 'Product removed from cart!');
    }

    /**
     * Update cart quantity
     */
    public function updateCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        
        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')
                         ->with('success', 'Cart updated!');
    }

    /**
     * Get products by category
     */
    public function byCategory($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
                          ->active()
                          ->paginate(12);
        
        return view('products.category', compact('products', 'category'));
    }
}