<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $services = Service::active()->get();
    
    // Check if this is a products page request
    $showAllProducts = request()->is('dashboard') || request()->has('categories') || request()->has('price_range');
    
    if ($showAllProducts) {
        // For dashboard or filtered requests, show all products with pagination
        $query = Product::with('category')->where('status', 'active');
        
        // Apply filters if any
        if ($request->has('categories')) {
            $categorySlugs = $request->input('categories');
            $query->whereHas('category', function($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }
        
        if ($priceRange = $request->input('price_range')) {
            switch ($priceRange) {
                case 'under50': $query->where('price', '<', 50); break;
                case '50-200': $query->whereBetween('price', [50, 200]); break;
                case 'over200': $query->where('price', '>', 200); break;
            }
        }
        
        $products = $query->paginate(12)->withQueryString();
    } else {
        // For home page, show limited products
        $products = Product::where('status', 'active')->take(8)->get();
    }
    
    // Get categories
    $categories = Category::all()->map(function ($c) {
        return [
            'id'   => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
        ];
    });
    
    return view('home.index', compact('services', 'products', 'categories'));
}
   public function products(Request $request)
{
    $services = Service::active()->get();
    // Get ACTIVE categories
    $categories = Category::where('is_active', true)
        ->get()
        ->map(function ($c) {
            return [
                'id'   => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ];
        });

    // Start query - get products with active categories
    $query = Product::with(['category' => function($query) {
        $query->where('is_active', true);
    }])->where('status', 'active');

    // Apply category filter
    if ($request->has('categories')) {
        $categorySlugs = $request->input('categories');
        $query->whereHas('category', function($q) use ($categorySlugs) {
            $q->where('is_active', true)
              ->whereIn('slug', $categorySlugs);
        });
    }

    // Apply price range filter
    if ($priceRange = $request->input('price_range')) {
        switch ($priceRange) {
            case 'under50':
                $query->where('price', '<', 50);
                break;
            case '50-200':
                $query->whereBetween('price', [50, 200]);
                break;
            case 'over200':
                $query->where('price', '>', 200);
                break;
        }
    }

    // Get filtered products with pagination
    $products = $query->paginate(12)->withQueryString();

    return view('home.index', compact('categories', 'services','products'));
}    
public function dashboard(Request $request)
{
    // Get services
    $services = Service::active()->get();
    
    // Get categories
    $categories = Category::all()->map(function ($c) {
        return [
            'id'   => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
        ];
    });
    
    // Get products with filtering
    $query = Product::with('category')->where('status', 'active');
    
    // Apply filters if any
    if ($request->has('categories')) {
        $categorySlugs = $request->input('categories');
        $query->whereHas('category', function($q) use ($categorySlugs) {
            $q->whereIn('slug', $categorySlugs);
        });
    }
    
    if ($priceRange = $request->input('price_range')) {
        switch ($priceRange) {
            case 'under50':
                $query->where('price', '<', 50);
                break;
            case '50-200':
                $query->whereBetween('price', [50, 200]);
                break;
            case 'over200':
                $query->where('price', '>', 200);
                break;
        }
    }
    
    $products = $query->paginate(12)->withQueryString();
    
    return view('home.index', compact('services', 'categories', 'products'));
}
}