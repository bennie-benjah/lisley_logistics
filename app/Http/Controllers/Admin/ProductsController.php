<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    // ==================== Get all products ====================
    public function apiIndex()
    {
        try {
            $products = Product::with('category')->get()->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'category_id' => $p->category_id,
                    'category' => $p->category ? ['id' => $p->category->id, 'name' => $p->category->name] : null,
                    'price' => $p->price,
                    'stock_quantity' => $p->stock_quantity,
                    'status' => $p->status,
                    'image' => $p->image ? asset("images/products/{$p->image}") : null,
                    'created_at' => $p->created_at,
                ];
            });

            return response()->json($products);
        } catch (\Throwable $e) {
            Log::error('Products API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products'
            ], 500);
        }
    }

    // ==================== Store new product ====================
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'status' => 'required|string|in:active,inactive',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        }

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'product' => [
                ...$product->toArray(),
                'image' => $product->image ? asset("images/products/{$product->image}") : null,
                'category' => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name] : null,
            ]
        ]);
    }

    // ==================== Update product ====================
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'status' => 'required|string|in:active,inactive',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && File::exists(public_path("images/products/{$product->image}"))) {
                File::delete(public_path("images/products/{$product->image}"));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'product' => [
                ...$product->toArray(),
                'image' => $product->image ? asset("images/products/{$product->image}") : null,
                'category' => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name] : null,
            ]
        ]);
    }

    // ==================== Delete product ====================
    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path("images/products/{$product->image}"))) {
            File::delete(public_path("images/products/{$product->image}"));
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    // ==================== Fetch single product ====================
    public function apiShow(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'category_id' => $product->category_id,
            'category' => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name] : null,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'status' => $product->status,
            'image' => $product->image ? asset("images/products/{$product->image}") : null,
            'created_at' => $product->created_at,
        ]);
    }
}
