<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    // Relationships
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_transit' => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'delayed' => 'Delayed'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getIconAttribute()
    {
        $icons = [
            'pending' => 'fas fa-clock',
            'processing' => 'fas fa-cogs',
            'in_transit' => 'fas fa-truck-moving',
            'out_for_delivery' => 'fas fa-truck',
            'delivered' => 'fas fa-check-circle',
            'delayed' => 'fas fa-exclamation-triangle'
        ];

        return $icons[$this->status] ?? 'fas fa-info-circle';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('F d, Y - h:i A');
    }

    // Scopes
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}