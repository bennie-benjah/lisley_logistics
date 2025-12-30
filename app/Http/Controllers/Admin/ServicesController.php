<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
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
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service (Admin).
     */
   public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string|max:100',
        'icon' => 'nullable|string|max:50',
        'status' => 'required|in:active,inactive',
        'features' => 'nullable|string',
        'image' => 'nullable|image|max:2048'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('services', 'public');
    }

    // Convert features string to array
    if ($request->filled('features')) {
        $validated['features'] = array_map('trim', explode(',', $request->features));
    }

    // Create the service
    $service = Service::create($validated);

    // For AJAX requests, return JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service created successfully!',
            'service' => $service
        ]);
    }

    // Redirect to the admin dashboard
    return redirect()->route('admin.index')
        ->with('success', 'Service created successfully!');
}

    /**
     * Display the specified service (Admin).
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service (Admin).
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service (Admin).
     */
   public function update(Request $request, Service $service)
{
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string|max:100',
        'icon' => 'nullable|string|max:50',
        'status' => 'required|in:active,inactive',
        'features' => 'nullable|string',
        'image' => 'nullable|image|max:2048'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $validated['image'] = $request->file('image')->store('services', 'public');
    }

    // Convert features string to array
    if ($request->filled('features')) {
        $validated['features'] = array_map('trim', explode(',', $request->features));
    }

    // Update the service
    $service->update($validated);

    // For AJAX requests, return JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully!',
            'service' => $service
        ]);
    }

    // Redirect to the admin dashboard (admin.index)
    return redirect()->route('admin.index')
        ->with('success', 'Service updated successfully!');
}
    /**
     * Remove the specified service (Admin).
     */
   public function destroy(Request $request, Service $service)
{
    // Delete image if exists
    if ($service->image) {
        Storage::disk('public')->delete($service->image);
    }

    // Delete the service
    $service->delete();

    // For AJAX requests, return JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully!'
        ]);
    }

    // For non-AJAX requests, redirect
    return redirect()->route('admin.index')
        ->with('success', 'Service deleted successfully!');
}
    /**
     * API: Get all services (for AJAX/JavaScript)
     */
    public function apiIndex()
    {
        $services = Service::latest()->get();
        return response()->json($services);
    }

    /**
     * API: Get specific service (for AJAX/JavaScript)
     */
    public function apiShow(Service $service)
    {
        return response()->json($service);
    }

    /**
     * Toggle service status (Active/Inactive)
     */
    public function toggleStatus(Service $service)
    {
        $service->status = $service->status === 'active' ? 'inactive' : 'active';
        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully',
            'status' => $service->status
        ]);
    }

    /**
     * Bulk actions on services
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $serviceIds = $request->input('service_ids', []);

        if (empty($serviceIds)) {
            return redirect()->back()->with('error', 'No services selected.');
        }

        switch ($action) {
            case 'activate':
                Service::whereIn('id', $serviceIds)->update(['status' => 'active']);
                $message = 'Selected services activated successfully.';
                break;

            case 'deactivate':
                Service::whereIn('id', $serviceIds)->update(['status' => 'inactive']);
                $message = 'Selected services deactivated successfully.';
                break;

            case 'delete':
                $services = Service::whereIn('id', $serviceIds)->get();
                foreach ($services as $service) {
                    if ($service->image) {
                        Storage::disk('public')->delete($service->image);
                    }
                    $service->delete();
                }
                $message = 'Selected services deleted successfully.';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export services to CSV
     */
    public function export(Request $request)
    {
        $services = Service::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="services_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($services) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Category', 'Status', 'Features', 'Created At']);

            // Add data rows
            foreach ($services as $service) {
                fputcsv($file, [
                    $service->id,
                    $service->name,
                    $service->category,
                    $service->status,
                    $service->features,
                    $service->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
