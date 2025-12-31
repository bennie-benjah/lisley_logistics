<?php
// App\Models\Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'category_id', 'price', 
        'image', 'stock_quantity', 'status'
    ];
    
    // Add these accessors
    public function getCategorySlugAttribute()
    {
        $categoryMap = [
            1 => 'packaging',
            2 => 'equipment',
            3 => 'equipment',
            6 => 'tracking'
        ];
        
        return $categoryMap[$this->category_id] ?? 'other';
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
}