<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'category', // Changed from category_id
        'image',
        'features',
        'status'
    ];

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // Return a default service image
        return asset('images/default-service.png');
    }

    public function getIconClassAttribute()
    {
        // Map service names to FontAwesome icons
        $iconMap = [
            'Freight Forwarding' => 'fas fa-plane',
            'Warehousing & Storage' => 'fas fa-warehouse',
            'Last-Mile Delivery' => 'fas fa-truck',
            'Supply Chain Management' => 'fas fa-link',
            'Customs Clearance' => 'fas fa-passport',
            'Real-Time Tracking' => 'fas fa-satellite',
            'Air Freight' => 'fas fa-plane-departure',
            'Sea Freight' => 'fas fa-ship',
            'Road Transportation' => 'fas fa-truck-moving',
            'Cold Chain Logistics' => 'fas fa-snowflake',
            'E-commerce Fulfillment' => 'fas fa-shopping-cart',
        ];

        return $iconMap[$this->name] ?? ($this->icon ?: 'fas fa-box');
    }

    // Accessor to get features as array
    public function getFeaturesArrayAttribute()
    {
        if (!$this->features) {
            return [];
        }
        return array_map('trim', explode(',', $this->features));
    }

    // Mutator to store features as comma-separated string
    public function setFeaturesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['features'] = implode(',', $value);
        } else {
            $this->attributes['features'] = $value;
        }
    }

    // No more category relationship since it's just a string field
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->limit(6);
    }
}
