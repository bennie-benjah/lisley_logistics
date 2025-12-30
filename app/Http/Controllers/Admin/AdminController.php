<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::role('user')->count();
        $totalServices = Service::count();
        $activeServices = Service::where('status', 'active')->count();
        
        return view('admin.index', compact('totalUsers', 'totalServices', 'activeServices'));
    }
    
    /**
     * Display customers list (users with 'user' role)
     */
    public function customers()
    {
        $customers = User::role('user')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }
    
    /**
     * API: Get customers list for AJAX
     */
    public function apiCustomers()
    {
        $customers = User::role('user')
            ->select('id', 'name', 'email', 'phone', 'created_at')
            ->withCount('shipments') // If you have shipments relationship
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A',
                    'shipments_count' => $user->shipments_count ?? 0,
                    'created_at' => $user->created_at->format('M d, Y'),
                    'created_at_raw' => $user->created_at
                ];
            });
        
        return response()->json($customers);
    }
    /**
 * Get customer details for modal
 */
public function customerDetails(User $user)
{
    if (!$user->hasRole('user')) {
        return response()->json(['error' => 'Not a customer'], 404);
    }

    $data = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'created_at' => $user->created_at->format('M d, Y'),
        'shipments_count' => $user->shipments()->count(),
        'active_shipments' => $user->shipments()->where('status', 'in-transit')->count(),
        'completed_shipments' => $user->shipments()->where('status', 'delivered')->count(),
        'last_shipment' => $user->shipments()->latest()->first()
    ];

    return response()->json($data);
}

/**
 * Get customer data for editing
 */
public function editCustomer(User $user)
{
    if (!$user->hasRole('user')) {
        return response()->json(['error' => 'Not a customer'], 404);
    }

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'status' => 'active' // You can add status field to users table if needed
    ]);
}

/**
 * Update customer
 */
public function updateCustomer(Request $request, User $user)
{
    if (!$user->hasRole('user')) {
        return response()->json(['error' => 'Not a customer'], 404);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20'
    ]);

    $user->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Customer updated successfully'
    ]);
}

/**
 * Get chart data for customer growth
 */
public function customerChartData()
{
    $months = [];
    $counts = [];
    
    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $months[] = $month->format('M');
        
        $count = User::role('user')
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->count();
        
        $counts[] = $count;
    }
    
    return response()->json([
        'months' => $months,
        'counts' => $counts
    ]);
}
    /**
     * Delete a customer (user with 'user' role)
     */
    public function destroyCustomer(Request $request, User $user)
    {
        // Check if user has 'user' role
        if (!$user->hasRole('user')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a customer'
                ], 403);
            }
            return back()->with('error', 'User is not a customer');
        }
        
        // Delete the user
        $user->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

}