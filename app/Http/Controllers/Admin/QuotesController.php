<?php
// app/Http\Controllers\Admin\QuotesController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuotesController extends Controller
{
    /**
     * Display quote requests page
     */
    public function index()
    {
        return view('admin.quotes');
    }

    /**
     * Get all quotes for the grid (JSON)
     */
    public function data(Request $request)
    {
        $query = Quote::query();

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('service') && $request->service) {
            $query->where('service', $request->service);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        // Get quotes with pagination
        $quotes = $query->orderBy('created_at', 'desc')->get();

        return response()->json($quotes->map(function($quote) {
            return [
                'id' => $quote->id,
                'name' => $quote->name,
                'email' => $quote->email,
                'phone' => $quote->phone,
                'company' => $quote->company,
                'service' => $quote->service,
                'service_name' => $this->getServiceName($quote->service),
                'details' => $quote->details,
                'budget' => $quote->budget,
                'status' => $quote->status,
                'status_badge' => $this->getStatusBadge($quote->status),
                'created_at' => $quote->created_at->format('M d, Y H:i'),
                'created_date' => $quote->created_at->format('Y-m-d'),
                'updated_at' => $quote->updated_at->format('M d, Y H:i')
            ];
        }));
    }

    /**
     * Get statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'total' => Quote::count(),
            'new' => Quote::where('status', 'new')->count(),
            'reviewed' => Quote::where('status', 'reviewed')->count(),
            'quoted' => Quote::where('status', 'quoted')->count(),
            'closed' => Quote::where('status', 'closed')->count(),
            'by_service' => Quote::select('service', DB::raw('count(*) as count'))
                ->groupBy('service')
                ->get()
                ->keyBy('service')
        ];

        return response()->json($stats);
    }

    /**
     * Update quote status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,reviewed,quoted,closed'
        ]);

        $quote = Quote::findOrFail($id);
        $quote->status = $request->status;
        $quote->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Delete a quote
     */
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quote deleted successfully'
        ]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:mark_reviewed,mark_quoted,mark_closed,delete'
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'mark_reviewed':
                Quote::whereIn('id', $ids)->update(['status' => 'reviewed']);
                $message = 'Selected quotes marked as reviewed';
                break;

            case 'mark_quoted':
                Quote::whereIn('id', $ids)->update(['status' => 'quoted']);
                $message = 'Selected quotes marked as quoted';
                break;

            case 'mark_closed':
                Quote::whereIn('id', $ids)->update(['status' => 'closed']);
                $message = 'Selected quotes marked as closed';
                break;

            case 'delete':
                Quote::whereIn('id', $ids)->delete();
                $message = 'Selected quotes deleted';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Helper: Get service display name
     */
    private function getServiceName($service)
    {
        $services = [
            'freight' => 'Freight Forwarding',
            'storage' => 'Storage & Warehousing',
            'delivery' => 'Last-Mile Delivery',
            'management' => 'Supply Chain Management',
            'international' => 'International Shipping',
            'technology' => 'Logistics Technology'
        ];

        return $services[$service] ?? ucfirst($service);
    }

    /**
     * Helper: Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'new' => '<span class="badge badge-new">New</span>',
            'reviewed' => '<span class="badge badge-reviewed">Reviewed</span>',
            'quoted' => '<span class="badge badge-quoted">Quoted</span>',
            'closed' => '<span class="badge badge-closed">Closed</span>'
        ];

        return $badges[$status] ?? '<span class="badge">Unknown</span>';
    }
}
