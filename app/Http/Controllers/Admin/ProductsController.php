<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request; // Make sure this is imported!
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    public function data()
    {
        return Product::with('category')->get()->map(function ($p) {
            return [
                'id'             => $p->id,
                'name'           => $p->name,
                'description'    => $p->description,
                'category_name'  => $p->category->name,
                'category_slug'  => strtolower($p->category->name),
                'price'          => (float) $p->price,

                'status'         => $p->status,
                'stock_quantity' => $p->stock_quantity,
                'image'          => $p->image,
            ];
        });
    }

    public function store(Request $request)
    {
        Log::info('=== PRODUCT STORE VALIDATION DEBUG ===');
        Log::info('Request data:', $request->except(['image'])); // Don't log file content

        try {
            $data = $request->validate([
                'name'           => 'required|string|max:255',
                'description'    => 'required|string',
                'category_id'    => 'required|exists:categories,id',
                'price'          => 'required|numeric|min:0',
                'status'         => 'required|in:active,inactive',
                'stock_quantity' => 'required|integer|min:0',
                'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info('Validation passed successfully');

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                Log::info('File validation:', [
                    'isValid'   => $file->isValid(),
                    'mime'      => $file->getMimeType(),
                    'size'      => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);

                if ($file->isValid()) {
                    $directory = public_path('images/products');
                    if (! File::exists($directory)) {
                        File::makeDirectory($directory, 0755, true);
                    }

                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($directory, $filename);
                    $data['image'] = $filename;

                    Log::info('Image saved: ' . $filename);
                }
            } else {
                $data['image'] = null;
            }

            $product = Product::create($data);

            Log::info('Product created: ' . $product->id);

            return response()->json([
                'success'    => true,
                'message'    => 'Product created successfully',
                'product_id' => $product->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation errors:', $e->errors());
            Log::error('Failed data:', $request->all());

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, Product $product)
    {
        try {
            $data = $request->validate([
                'name'           => 'required|string|max:255',
                'description'    => 'required|string',
                'category_id'    => 'required|exists:categories,id',
                'price'          => 'required|numeric|min:0',
                'status'         => 'required|in:active,inactive',
                'stock_quantity' => 'required|integer|min:0',
                'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Create directory if it doesn't exist
                $directory = public_path('images/products');
                if (! File::exists($directory)) {
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

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage(),
            ], 500);
        }
    }
    // Add this method to your ProductsController class
public function destroy(Product $product)
{
    try {
        // Delete the product image if it exists
        if ($product->image) {
            $imagePath = public_path('images/products/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Delete the product
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Product delete error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete product: ' . $e->getMessage()
        ], 500);
    }
}
}
