<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['parent', 'creator'])
                             ->orderBy('display_order')
                             ->orderBy('name')
                             ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->orderBy('name')
                                   ->get();

        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|size:7|starts_with:#',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0'
        ]);

        // Generate slug from name
        $validated['slug'] = Category::generateSlug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Add created by user
        $validated['created_by'] = Auth::id();

        Category::create($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $category = Category::with(['products', 'services', 'children', 'parent'])
                           ->where('slug', $slug)
                           ->firstOrFail();

        // Get products and services from this category and all subcategories
        $categoryIds = $category->descendant_ids;

        $products = Product::whereIn('category_id', $categoryIds)
                          ->where('status', 'active')
                          ->orderBy('created_at', 'desc')
                          ->paginate(12, ['*'], 'products_page');

        $services = Service::whereIn('category_id', $categoryIds)
                          ->where('status', 'active')
                          ->orderBy('created_at', 'desc')
                          ->paginate(12, ['*'], 'services_page');

        return view('categories.show', compact('category', 'products', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::where('id', '!=', $id)
                                   ->whereNull('parent_id')
                                   ->orderBy('name')
                                   ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|size:7|starts_with:#',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0'
        ]);

        // Generate new slug if name changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Category::generateSlug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category can be deleted
        if (!$category->canBeDeleted()) {
            return redirect()->route('categories.index')
                             ->with('error', 'Cannot delete category. It contains products, services, or subcategories.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully!');
    }

    /**
     * Get categories for dropdown
     */
    public function getCategories(Request $request)
    {
        $categories = Category::active()->ordered()->get();

        $options = [];
        foreach ($categories as $category) {
            $prefix = str_repeat('-- ', $category->depth);
            $options[] = [
                'id' => $category->id,
                'text' => $prefix . $category->name
            ];
        }

        return response()->json($options);
    }

    /**
     * Get category tree
     */
    public function getCategoryTree()
    {
        $tree = Category::getTreeStructure();

        return response()->json($tree);
    }

    /**
     * Get main categories (no parent)
     */
    public function mainCategories()
    {
        $categories = Category::whereNull('parent_id')
                             ->active()
                             ->ordered()
                             ->get();

        return view('categories.main', compact('categories'));
    }

    /**
     * Search categories
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $categories = Category::where('name', 'like', "%{$searchTerm}%")
                             ->orWhere('description', 'like', "%{$searchTerm}%")
                             ->orderBy('display_order')
                             ->orderBy('name')
                             ->paginate(20);

        return view('categories.search', compact('categories', 'searchTerm'));
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $status = $category->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Category {$status} successfully!");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $category = Category::findOrFail($id);
        $category->is_featured = !$category->is_featured;
        $category->save();

        $status = $category->is_featured ? 'marked as featured' : 'removed from featured';

        return back()->with('success', "Category {$status} successfully!");
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $categoryId) {
            Category::where('id', $categoryId)->update(['display_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function apiIndex()
{
    return Category::select('id', 'name', 'slug')->get();
}


}
