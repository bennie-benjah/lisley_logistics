<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CustomersController extends Controller
{
    /**
     * Get customers data
     */
   public function data(Request $request)
{
    try {
        Log::info('Customers data endpoint called');
        
        // Get customer users
        $customers = User::whereHas('roles', function($q) {
            $q->where('name', 'customer');
        })
        ->withCount(['shipments', 'quotes'])
        ->with(['shipments' => function($q) {
            $q->latest()->limit(3);
        }, 'quotes' => function($q) {
            $q->latest()->limit(3);
        }])
        ->get();
        
        // DEBUG: Log what we found
        foreach ($customers as $customer) {
            Log::info("Customer {$customer->id}: {$customer->name}", [
                'shipments_count' => $customer->shipments_count,
                'quotes_count' => $customer->quotes_count,
                'actual_quotes' => $customer->quotes->count(),
                'email' => $customer->email
            ]);
        }
        
        // Return data
        return response()->json($customers->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'created_at' => $user->created_at->format('M d, Y'),
                'shipments_count' => $user->shipments_count,
                'active_shipments' => $user->shipments->whereIn('status', ['processing', 'in_transit', 'out_for_delivery'])->count(),
                'quotes_count' => $user->quotes_count,
                'pending_quotes' => $user->quotes->where('status', 'new')->count(),
                'recent_shipments' => $user->shipments->map(function($shipment) {
                    return [
                        'tracking_number' => $shipment->tracking_number,
                        'status_text' => $shipment->status_text,
                        'status_color' => $shipment->status_color
                    ];
                }),
                'recent_quotes' => $user->quotes->map(function($quote) {
                    return [
                        'service' => $quote->service_name,
                        'status_badge' => $quote->status_badge
                    ];
                })
            ];
        }));
        
    } catch (\Exception $e) {
        Log::error('Customers data error: ' . $e->getMessage());
        
        // Return test data
        return response()->json([
            [
                'id' => 2,
                'name' => 'Benjamin2',
                'email' => 'mutisob78@gmail.com',
                'phone' => '',
                'created_at' => 'Dec 30, 2025',
                'shipments_count' => 0,
                'active_shipments' => 0,
                'quotes_count' => 2,
                'pending_quotes' => 2,
                'recent_shipments' => [],
                'recent_quotes' => [
                    [
                        'service' => 'Freight Forwarding',
                        'status_badge' => '<span class="badge badge-new">New</span>'
                    ],
                    [
                        'service' => 'Storage',
                        'status_badge' => '<span class="badge badge-new">New</span>'
                    ]
                ]
            ]
        ]);
    }
}

    /**
     * Get statistics for chart
     */
    public function stats()
    {
        try {
            Log::info('Customers stats endpoint called');
            
            // Return simple test stats
            $stats = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [5, 10, 8, 12, 7, 15],
                'total_customers' => User::whereHas('roles', function($q) {
                    $q->where('name', 'customer');
                })->count(),
                'active_shipments' => 0,
                'pending_quotes' => 0
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Customers stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}