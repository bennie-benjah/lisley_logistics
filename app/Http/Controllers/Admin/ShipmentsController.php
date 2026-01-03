<?php

/**
     * Display a listing of services (Admin).
     */
 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipmentsController extends Controller
{
    /**
     * Display a listing of services (Admin).
     */
   public function index()
{
    $services = Service::all();
    return view('admin.index', compact('services'));
}
    /**
     * Show the form for creating a new service (Admin).
     */
   // In ShipmentController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'tracking_number' => 'required|unique:shipments',
        'user_id' => 'required|exists:users,id',
        'receiver_name' => 'required|string|max:255',
        'receiver_email' => 'required|email',
        'description' => 'required|string',
        'status' => 'required|in:pending,processing,in_transit,out_for_delivery,delivered,delayed',
        'weight' => 'nullable|numeric|min:0.01',
        'estimated_delivery' => 'nullable|date',
        'actual_delivery' => 'nullable|date|after_or_equal:estimated_delivery'
    ]);
    
    Shipment::create($validated);
    
    return response()->json([
        'success' => true,
        'message' => 'Shipment created successfully'
    ]);
}

public function update(Request $request, $id)
{
    $shipment = Shipment::findOrFail($id);
    
    $validated = $request->validate([
        'tracking_number' => 'required|unique:shipments,tracking_number,' . $id,
        'user_id' => 'required|exists:users,id',
        'receiver_name' => 'required|string|max:255',
        'receiver_email' => 'required|email',
        'description' => 'required|string',
        'status' => 'required|in:pending,processing,in_transit,out_for_delivery,delivered,delayed',
        'weight' => 'nullable|numeric|min:0.01',
        'estimated_delivery' => 'nullable|date',
        'actual_delivery' => 'nullable|date|after_or_equal:estimated_delivery'
    ]);
    
    $shipment->update($validated);
    
    return response()->json([
        'success' => true,
        'message' => 'Shipment updated successfully'
    ]);
}
}
