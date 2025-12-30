<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public function data()
    {
        return Product::with('category')->get()->map(function($p){
            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'category_name' => $p->category->name,
                'category_slug' => strtolower($p->category->name),
                'price' => (float) $p->price,

                'status' => $p->status,
                'stock_quantity' => $p->stock_quantity,
                'image' => $p->image
            ];
        });
    }

     public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive',
                'stock_quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                // Create directory if it doesn't exist
                $directory = public_path('images/products');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                
                // Generate unique filename
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();
                
                // Move file to public/images/products
                $request->file('image')->move($directory, $filename);
                
                // Store only the filename in database
                $data['image'] = $filename;
            } else {
                $data['image'] = null;
            }

            Product::create($data);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            \Log::error('Product store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive',
                'stock_quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                // Create directory if it doesn't exist
                $directory = public_path('images/products');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                
                // Delete old image if exists
                if ($product->image && file_exists($directory . '/' . $product->image)) {
                    unlink($directory . '/' . $product->image);
                }
                
                // Generate unique filename
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();
                
                // Move file to public/images/products
                $request->file('image')->move($directory, $filename);
                
                // Store only the filename in database
                $data['image'] = $filename;
            } else {
                // Keep existing image if not uploading new one
                $data['image'] = $product->image;
            }

            $product->update($data);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
