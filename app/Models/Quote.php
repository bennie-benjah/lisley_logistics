<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'service',
        'details',
        'company',
        'budget',
        'status',
        'user_id'  // Add this to relate quotes to users
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    /**
     * Quote belongs to a user (customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'new' => '<span class="badge badge-new">New</span>',
            'reviewed' => '<span class="badge badge-reviewed">Reviewed</span>',
            'quoted' => '<span class="badge badge-quoted">Quoted</span>',
            'closed' => '<span class="badge badge-closed">Closed</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge">Unknown</span>';
    }

    /**
     * Get service display name
     */
    public function getServiceNameAttribute()
    {
        $services = [
            'freight' => 'Freight Forwarding',
            'storage' => 'Storage & Warehousing',
            'delivery' => 'Last-Mile Delivery',
            'management' => 'Supply Chain Management',
            'international' => 'International Shipping',
            'technology' => 'Logistics Technology'
        ];

        return $services[$this->service] ?? ucfirst($this->service);
    }
}
