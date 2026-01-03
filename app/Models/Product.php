<?php
// App\Models\Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'category_id', 'price',
        'image', 'stock_quantity', 'status'
    ];

    // FIX THIS ACCESSOR - Use category->slug not category->name
    public function getCategorySlugAttribute()
    {
        return $this->category
            ? $this->category->slug  // Get the actual slug from category
            : null;
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-product.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('images/products/' . $this->image);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}