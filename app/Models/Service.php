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
        'category_id',
        'image',
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
        ];
        
        return $iconMap[$this->name] ?? ($this->icon ?: 'fas fa-box');
    }

    public function getQuoteUrlAttribute()
    {
        return route('services.quote', $this->id);
    }

    public function getTrackUrlAttribute()
    {
        return route('shipments.track');
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeFeatured($query)
    {
        // Could add a 'is_featured' field if needed
        return $query->limit(6); // Show 6 featured services
    }
}