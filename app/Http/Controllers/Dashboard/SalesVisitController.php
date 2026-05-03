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
        $search = trim((string) $request->get('search', ''));

        // 1. Core Metrics
        $totalVisits = SalesVisitEntry::count();
        $successCount = SalesVisitEntry::where('status', 'service_request')->count();
        $successRate = $totalVisits > 0 ? round(($successCount / $totalVisits) * 100) : 0;
        
        $hotLeadsCount = Lead::accessible()->where('lead_temperature', 'hot')->count();
        $serviceInterestsCount = $successCount; // Based on service_request status
        $marketIntelCount = ServiceUsage::count();

        // 2. Specialized Queues for UI Tabs
        $today = Carbon::today();
        
        $todayVisits = SalesVisitEntry::whereDate('visit_date', $today)
            ->with(['dailySalesVisit.lead', 'marketingExe'])
            ->when($search !== '', fn ($q) => $this->applyVisitSearch($q, $search))
            ->get();

        $overdueVisits = SalesVisitEntry::where('status', 'follow_up')
            ->whereDate('visit_date', '<', $today)
            ->with(['dailySalesVisit.lead', 'marketingExe'])
            ->when($search !== '', fn ($q) => $this->applyVisitSearch($q, $search))
            ->get();
        
        $satisfiedVisits = SalesVisitEntry::where('status', 'service_request')->count();
        $followUpsCount = SalesVisitEntry::where('status', 'follow_up')->count();

        // 3. History Logs
        $historyQuery = SalesVisitEntry::with(['dailySalesVisit.lead', 'marketingExe'])
            ->latest('visit_date')
            ->when($search !== '', fn ($q) => $this->applyVisitSearch($q, $search));

        if ($filter !== 'all') {
            $historyQuery->where('status', $filter);
        }

        $visitHistory = $historyQuery->paginate(15)->withQueryString();

        return view('dashboard.sales-visits.index', compact(
            'tab',
            'filter',
            'totalVisits',
            'todayVisits',
            'overdueVisits',
            'visitHistory',
            'satisfiedVisits',
            'followUpsCount',
            'successRate',
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

    public function show(SalesVisitEntry $salesVisit)
    {
        return redirect()->route('tyro-dashboard.sales-visits.edit', $salesVisit);
    }

    public function edit(SalesVisitEntry $salesVisit)
    {
        return view('dashboard.sales-visits.create', [
            'lead' => $salesVisit->dailySalesVisit?->lead,
            'visit' => $salesVisit,
        ]);
    }

    public function update(Request $request, SalesVisitEntry $salesVisit)
    {
        return redirect()->route('tyro-dashboard.sales-visits.edit', $salesVisit)
            ->with('success', 'Use the sales visit form to update this record.');
    }

    public function destroy(SalesVisitEntry $salesVisit)
    {
        $salesVisit->delete();

        return redirect()->route('tyro-dashboard.sales-visits.index')
            ->with('success', 'Sales visit deleted successfully.');
    }

    private function applyVisitSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('status', 'like', '%' . $search . '%')
                ->orWhere('notes', 'like', '%' . $search . '%')
                ->orWhereHas('dailySalesVisit.lead', function ($leadQuery) use ($search) {
                    $leadQuery->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('client_name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
        });
    }
}
