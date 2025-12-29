<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ShipmentUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ShipmentUpdate::with(['shipment', 'updater'])
                              ->orderBy('created_at', 'desc');

        // Filter by shipment ID
        if ($request->has('shipment_id')) {
            $query->where('shipment_id', $request->shipment_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by updater type
        if ($request->has('updated_by_type')) {
            $query->where('updated_by_type', $request->updated_by_type);
        }

        $updates = $query->paginate(50);

        return view('shipment-updates.index', compact('updates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $shipmentId = $request->get('shipment_id');
        $shipment = null;
        
        if ($shipmentId) {
            $shipment = Shipment::findOrFail($shipmentId);
            
            // Check authorization
            if (!Auth::user()->is_admin && $shipment->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }
            
            $nextStatusOptions = ShipmentUpdate::getNextStatusOptions($shipment->status);
        } else {
            $nextStatusOptions = [];
        }

        return view('shipment-updates.create', compact('shipment', 'nextStatusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'status' => 'required|in:pending,processing,in_transit,out_for_delivery,delivered,delayed,cancelled,returned',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'estimated_arrival' => 'nullable|date|after:now'
        ]);

        $shipment = Shipment::findOrFail($validated['shipment_id']);
        
        // Check authorization
        if (!Auth::user()->is_admin && $shipment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Create the update
        $update = ShipmentUpdate::createFromStatusChange(
            $shipment,
            $validated['status'],
            $validated['location'],
            $validated['notes'] ?? null,
            Auth::user(),
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );

        // Update shipment status and location
        $shipment->update([
            'status' => $validated['status'],
            'current_location' => $validated['location']
        ]);

        // If delivered, update actual delivery time
        if ($validated['status'] === 'delivered') {
            $shipment->update(['actual_delivery' => now()]);
        }

        // If estimated arrival is provided, update it
        if (!empty($validated['estimated_arrival'])) {
            $update->estimated_arrival = $validated['estimated_arrival'];
            $update->save();
            
            $shipment->update(['estimated_delivery' => $validated['estimated_arrival']]);
        }

        return redirect()->route('shipments.show', $shipment->id)
                         ->with('success', 'Shipment update added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $update = ShipmentUpdate::with(['shipment', 'updater'])
                               ->findOrFail($id);
        
        // Check authorization
        if (!Auth::user()->is_admin && $update->shipment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('shipment-updates.show', compact('update'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $update = ShipmentUpdate::with('shipment')->findOrFail($id);
        
        // Only admin can edit updates
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $nextStatusOptions = ShipmentUpdate::getNextStatusOptions($update->shipment->status);

        return view('shipment-updates.edit', compact('update', 'nextStatusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $update = ShipmentUpdate::with('shipment')->findOrFail($id);
        
        // Only admin can edit updates
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,in_transit,out_for_delivery,delivered,delayed,cancelled,returned',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'estimated_arrival' => 'nullable|date'
        ]);

        // Update the update record
        $update->update($validated);

        // Update shipment if this is the latest update
        $latestUpdate = ShipmentUpdate::getLatestUpdate($update->shipment_id);
        if ($latestUpdate && $latestUpdate->id === $update->id) {
            $update->shipment->update([
                'status' => $validated['status'],
                'current_location' => $validated['location']
            ]);
        }

        return redirect()->route('shipment-updates.show', $update->id)
                         ->with('success', 'Update modified successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $update = ShipmentUpdate::with('shipment')->findOrFail($id);
        
        // Only admin can delete updates
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        // Check if this is the latest update
        $latestUpdate = ShipmentUpdate::getLatestUpdate($update->shipment_id);
        
        $update->delete();

        // If this was the latest update, revert shipment to previous status
        if ($latestUpdate && $latestUpdate->id === $update->id) {
            $previousUpdate = ShipmentUpdate::getLatestUpdate($update->shipment_id);
            
            if ($previousUpdate) {
                $update->shipment->update([
                    'status' => $previousUpdate->status,
                    'current_location' => $previousUpdate->location
                ]);
            }
        }

        return redirect()->route('shipments.show', $update->shipment_id)
                         ->with('success', 'Update deleted successfully!');
    }

    /**
     * API endpoint to get updates for a shipment
     */
    public function apiUpdates($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);
        
        // Check authorization
        if (!Auth::user()->is_admin && $shipment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updates = ShipmentUpdate::where('shipment_id', $shipmentId)
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->map(function ($update) {
                                    return [
                                        'id' => $update->id,
                                        'status' => $update->status,
                                        'status_text' => $update->status_text,
                                        'location' => $update->location,
                                        'notes' => $update->notes,
                                        'time' => $update->formatted_created_at,
                                        'time_ago' => $update->time_ago,
                                        'icon' => $update->icon,
                                        'updater_name' => $update->updater_name,
                                        'has_coordinates' => $update->has_coordinates,
                                        'coordinates' => $update->coordinates
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => $updates,
            'shipment' => [
                'id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'current_status' => $shipment->status,
                'current_location' => $shipment->current_location
            ]
        ]);
    }

    /**
     * Get shipment timeline
     */
    public function timeline($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);
        
        // Check authorization
        if (!Auth::user()->is_admin && $shipment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $timeline = ShipmentUpdate::getTimeline($shipmentId);

        return view('shipment-updates.timeline', compact('shipment', 'timeline'));
    }

    /**
     * Bulk update shipments (admin only)
     */
    public function bulkUpdate(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'shipment_ids' => 'required|array',
            'shipment_ids.*' => 'exists:shipments,id',
            'status' => 'required|in:in_transit,out_for_delivery,delivered,delayed',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $count = 0;
        foreach ($validated['shipment_ids'] as $shipmentId) {
            $shipment = Shipment::find($shipmentId);
            
            // Create update
            ShipmentUpdate::createFromStatusChange(
                $shipment,
                $validated['status'],
                $validated['location'],
                $validated['notes'] ?? null,
                Auth::user()
            );

            // Update shipment
            $shipment->update([
                'status' => $validated['status'],
                'current_location' => $validated['location']
            ]);

            $count++;
        }

        return back()->with('success', "{$count} shipments updated successfully!");
    }

    /**
     * Get updates by driver
     */
    public function byDriver($driverId)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $updates = ShipmentUpdate::where('updated_by', $driverId)
                                ->where('updated_by_type', 'driver')
                                ->with(['shipment'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(50);

        $driver = User::findOrFail($driverId);

        return view('shipment-updates.by-driver', compact('updates', 'driver'));
    }

    /**
     * Get recent updates for dashboard
     */
    public function recentUpdates()
    {
        $updates = ShipmentUpdate::with(['shipment'])
                                ->orderBy('created_at', 'desc')
                                ->limit(20)
                                ->get();

        return view('dashboard.updates', compact('updates'));
    }

    /**
     * Export updates to CSV
     */
    public function export(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $query = ShipmentUpdate::with(['shipment', 'updater']);

        // Apply filters
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $updates = $query->orderBy('created_at', 'desc')->get();

        $csv = "Update ID,Shipment ID,Tracking Number,Status,Location,Notes,Latitude,Longitude,Updated By,Updated At\n";

        foreach ($updates as $update) {
            $csv .= "\"{$update->id}\",";
            $csv .= "\"{$update->shipment_id}\",";
            $csv .= "\"" . ($update->shipment->tracking_number ?? 'N/A') . "\",";
            $csv .= "\"{$update->status_text}\",";
            $csv .= "\"{$update->location}\",";
            $csv .= "\"" . str_replace('"', '""', $update->notes ?? '') . "\",";
            $csv .= "\"{$update->latitude}\",";
            $csv .= "\"{$update->longitude}\",";
            $csv .= "\"{$update->updater_name}\",";
            $csv .= "\"{$update->created_at}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="shipment-updates-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Get statistics for updates
     */
    public function statistics()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $today = Carbon::today();
        
        $stats = [
            'total_updates' => ShipmentUpdate::count(),
            'updates_today' => ShipmentUpdate::whereDate('created_at', $today)->count(),
            'updates_this_week' => ShipmentUpdate::where('created_at', '>=', $today->subDays(7))->count(),
            'updates_this_month' => ShipmentUpdate::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count(),
            
            'by_status' => ShipmentUpdate::select('status', \DB::raw('count(*) as count'))
                                        ->groupBy('status')
                                        ->get()
                                        ->pluck('count', 'status'),
            
            'by_updater_type' => ShipmentUpdate::select('updated_by_type', \DB::raw('count(*) as count'))
                                              ->groupBy('updated_by_type')
                                              ->get()
                                              ->pluck('count', 'updated_by_type'),
            
            'recent_activity' => ShipmentUpdate::with(['shipment', 'updater'])
                                              ->orderBy('created_at', 'desc')
                                              ->limit(10)
                                              ->get()
        ];

        return view('shipment-updates.statistics', compact('stats'));
    }
}