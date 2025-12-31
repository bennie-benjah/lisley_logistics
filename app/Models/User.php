<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'  // Add this field for customer phone
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->assignRole('user');
        });
    }

    // Add these relationships:

    /**
     * Get all shipments for this user
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get all quote requests for this user
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Get the count of active shipments
     */
    public function getActiveShipmentsCountAttribute()
    {
        return $this->shipments()->whereIn('status', ['processing', 'in_transit', 'out_for_delivery'])->count();
    }

    /**
     * Get the count of delivered shipments
     */
    public function getDeliveredShipmentsCountAttribute()
    {
        return $this->shipments()->where('status', 'delivered')->count();
    }

    /**
     * Get the count of pending quotes
     */
    public function getPendingQuotesCountAttribute()
    {
        return $this->quotes()->where('status', 'new')->count();
    }

    /**
     * Get the count of total quotes
     */
    public function getTotalQuotesCountAttribute()
    {
        return $this->quotes()->count();
    }

    /**
     * Scope to get only customers (users with customer role)
     */
    public function scopeCustomers($query)
    {
        return $query->role('customer');
    }

    /**
     * Get user's role names as string
     */
    public function getRoleNamesAttribute()
    {
        return $this->roles->pluck('name')->implode(', ');
    }
}
