<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'user_id',
        'sender_name',
        'sender_email',
        'receiver_name',
        'receiver_email',
        'description',
        'weight',
        'status',
        'current_location',
        'estimated_delivery',
        'actual_delivery'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(ShipmentUpdate::class)->orderBy('created_at', 'asc');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'in_transit' => 'primary',
            'out_for_delivery' => 'success',
            'delivered' => 'success',
            'delayed' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_transit' => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'delayed' => 'Delayed'
        ];
        
        return $texts[$this->status] ?? 'Unknown';
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
        
        return $icons[$this->status] ?? 'fas fa-box';
    }

    public function getIsDelayedAttribute()
    {
        if ($this->status === 'delayed') {
            return true;
        }
        
        if (in_array($this->status, ['in_transit', 'out_for_delivery']) && 
            $this->estimated_delivery && 
            $this->estimated_delivery->isPast()) {
            return true;
        }
        
        return false;
    }

    public function getFormattedEstimatedDeliveryAttribute()
    {
        return $this->estimated_delivery 
            ? $this->estimated_delivery->format('F d, Y')
            : 'Not Available';
    }

    public function getTimelineAttribute()
    {
        $updates = $this->updates;
        $timeline = [];
        
        foreach ($updates as $update) {
            $timeline[] = [
                'title' => $update->status_text,
                'time' => $update->created_at->format('F d, Y - h:i A'),
                'description' => $update->notes,
                'icon' => $update->icon,
                'is_completed' => true,
                'is_current' => $update->id === $updates->last()->id
            ];
        }
        
        return $timeline;
    }

    // Scopes
    public function scopeByTracking($query, $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['processing', 'in_transit', 'out_for_delivery']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}