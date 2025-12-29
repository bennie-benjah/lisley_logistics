<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display tracking form
     */
    public function track()
    {
        return view('shipments.track');
    }

    /**
     * Handle tracking request
     */
    public function trackResult(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:50'
        ]);

        $shipment = Shipment::with('updates')
                           ->where('tracking_number', $request->tracking_number)
                           ->first();

        if (!$shipment) {
            return back()->with('error', 'Tracking number not found. Please check and try again.');
        }

        return view('shipments.track-result', compact('shipment'));
    }

    /**
     * Display user's shipments
     */
    public function index()
    {
        $shipments = Shipment::where('user_id', auth()->id())
                            ->with(['updates' => function($query) {
                                $query->latest()->limit(1);
                            }])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    /**
     * Display a single shipment
     */
    public function show($id)
    {
        $shipment = Shipment::with('updates')
                           ->findOrFail($id);

        // Check authorization
        if ($shipment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('shipments.show', compact('shipment'));
    }

    /**
     * Create new shipment
     */
    public function create()
    {
        return view('shipments.create');
    }

    /**
     * Store new shipment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_email' => 'required|email|max:255',
            'description' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'current_location' => 'required|string|max:255',
            'estimated_delivery' => 'nullable|date|after:today'
        ]);

        // Generate tracking number
        $trackingNumber = 'LL' . strtoupper(substr(md5(uniqid()), 0, 8)) . date('md');

        // Create shipment
        $shipment = Shipment::create([
            'tracking_number' => $trackingNumber,
            'user_id' => auth()->id(),
            'sender_name' => $validated['sender_name'],
            'sender_email' => $validated['sender_email'],
            'receiver_name' => $validated['receiver_name'],
            'receiver_email' => $validated['receiver_email'],
            'description' => $validated['description'],
            'weight' => $validated['weight'],
            'status' => 'pending',
            'current_location' => $validated['current_location'],
            'estimated_delivery' => $validated['estimated_delivery'] ?? null
        ]);

        // Create initial status update
        $shipment->updates()->create([
            'status' => 'pending',
            'location' => $validated['current_location'],
            'notes' => 'Shipment information received'
        ]);

        return redirect()->route('shipments.show', $shipment->id)
                         ->with('success', 'Shipment created! Tracking number: ' . $trackingNumber);
    }

    /**
     * Get shipment tracking timeline (API)
     */
    public function timeline($id)
    {
        $shipment = Shipment::with('updates')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'shipment' => [
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->status_text,
                'status_color' => $shipment->status_color,
                'current_location' => $shipment->current_location,
                'estimated_delivery' => $shipment->formatted_estimated_delivery,
                'is_delayed' => $shipment->is_delayed
            ],
            'timeline' => $shipment->timeline
        ]);
    }
}