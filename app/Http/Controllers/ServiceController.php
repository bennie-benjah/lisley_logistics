<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('category')->active()->get();
        $categories = Category::where('is_active', true)->get();
        
        return view('services.index', compact('services', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::with('category')->findOrFail($id);
        $relatedServices = Service::where('category_id', $service->category_id)
                                 ->where('id', '!=', $service->id)
                                 ->active()
                                 ->limit(3)
                                 ->get();
        
        return view('services.show', compact('service', 'relatedServices'));
    }

    /**
     * Show quote form for a service
     */
    public function showQuote($id)
    {
        $service = Service::findOrFail($id);
        
        return view('services.quote', compact('service'));
    }

    /**
     * Submit service quote request
     */
    public function submitQuote(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'details' => 'required|string',
            'estimated_volume' => 'nullable|string|max:100',
        ]);
        
        // Here you would typically:
        // 1. Save the quote request to database
        // 2. Send email notification
        // 3. Maybe create a task in your CRM
        
        // For now, just show success message
        return redirect()->route('services.show', $service->id)
                         ->with('success', 'Your quote request has been submitted! We\'ll contact you soon.');
    }

    /**
     * Get services by category
     */
    public function byCategory($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $services = Service::where('category_id', $category->id)
                          ->active()
                          ->get();
        
        return view('services.category', compact('services', 'category'));
    }

    /**
     * Search services
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        
        $services = Service::where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%")
                          ->active()
                          ->get();
        
        return view('services.search', compact('services', 'searchTerm'));
    }
}