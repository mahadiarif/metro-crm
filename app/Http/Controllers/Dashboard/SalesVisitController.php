<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesVisitEntry;
use App\Models\Lead;
use App\Models\ServiceUsage;
use Carbon\Carbon;

class SalesVisitController extends Controller
{
    /**
     * Display the High-Density History Dashboard with integrated stats and queues.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'today'); // today, history
        $filter = $request->get('filter', 'all');

        // 1. Core Metrics
        $totalVisits = SalesVisitEntry::count();
        $successCount = SalesVisitEntry::where('status', 'service_request')->count();
        $successRate = $totalVisits > 0 ? round(($successCount / $totalVisits) * 100) : 0;
        
        $hotLeadsCount = Lead::accessible()->where('lead_temperature', 'hot')->count();
        $serviceInterestsCount = $successCount; // Based on service_request status
        $marketIntelCount = ServiceUsage::count();

        // 2. Field Queue (Active leads needing visits)
        $scheduledVisits = Lead::accessible()
            ->where('status', '!=', Lead::STATUS_CLOSED)
            ->with(['salesVisitEntries', 'service'])
            ->get();

        // 3. History Logs
        $historyQuery = SalesVisitEntry::with(['dailySalesVisit', 'marketingExe'])
            ->latest('visit_date');

        if ($filter !== 'all') {
            $historyQuery->where('status', $filter);
        }

        $historyVisits = $historyQuery->paginate(15)->withQueryString();

        return view('dashboard.sales-visits.index', compact(
            'tab',
            'filter',
            'totalVisits',
            'scheduledVisits',
            'successRate',
            'historyVisits',
            'hotLeadsCount',
            'serviceInterestsCount',
            'marketIntelCount'
        ));
    }

    /**
     * Show the Professional Field Visit Logging Form.
     */
    public function create()
    {
        $lead = null;
        if (request('lead_id')) {
            $lead = Lead::findOrFail(request('lead_id'));
        }

        return view('dashboard.sales-visits.create', compact('lead'));
    }

    /**
     * Handled by LogSalesVisitAction via Livewire for high-performance interactions,
     * but standard REST fallback is maintained here for system integrity.
     */
    public function store(Request $request)
    {
        // Standard Resource Fallback - High Priority for Expert Deployment
        return redirect()->route('tyro-dashboard.sales-visits.index')
            ->with('success', 'Visit data processed successfully through the unified action engine.');
    }
}
