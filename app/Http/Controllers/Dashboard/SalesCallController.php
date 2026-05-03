<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesCall;
use App\Models\Lead;
use App\Models\ServiceUsage;
use Carbon\Carbon;

class SalesCallController extends Controller
{
    /**
     * Display a listing of sales calls with integrated filtering and stats.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'today'); // today, overdue, history
        $filter = $request->get('filter', 'all');
        $search = trim((string) $request->get('search', ''));

        // 1. Calculate Core Metrics (Aggregated)
        $totalCalls = SalesCall::count();
        $reachedCount = SalesCall::where('outcome', 'service_request')->count();
        $reachRate = $totalCalls > 0 ? round(($reachedCount / $totalCalls) * 100) : 0;
        
        $hotLeadsCount = Lead::accessible()->where('lead_temperature', 'hot')->count();
        $followUpsCount = SalesCall::where('outcome', 'follow_up')->count();
        $serviceInterestsCount = $reachedCount; // Calls resulting in service request
        $marketIntelCount = ServiceUsage::count();

        // 2. Fetch Queues based on Next Follow-up Dates (Logic matching the modern view)
        $today = Carbon::today();
        
        // Today's Queue (Scheduled for today or with no prior calls)
        $todayCalls = Lead::accessible()
            ->where(function($q) use ($today) {
                $q->whereDate('next_followup_at', $today)
                  ->orWhereNull('next_followup_at');
            })
            ->where('status', '!=', Lead::STATUS_CLOSED)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('client_name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->with(['assignedUser', 'latestCall', 'service'])
            ->get();

        // Overdue Queue (Scheduled before today)
        $overdueCalls = Lead::accessible()
            ->whereDate('next_followup_at', '<', $today)
            ->where('status', '!=', Lead::STATUS_CLOSED)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('client_name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->with(['assignedUser', 'latestCall', 'service'])
            ->get();

        // 3. History Feed (Logged interactions)
        $historyQuery = SalesCall::with(['lead', 'user'])->latest()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('outcome', 'like', '%' . $search . '%')
                        ->orWhereHas('lead', function ($leadQuery) use ($search) {
                            $leadQuery->where('company_name', 'like', '%' . $search . '%')
                                ->orWhere('client_name', 'like', '%' . $search . '%')
                                ->orWhere('phone', 'like', '%' . $search . '%');
                        });
                });
            });
        
        if ($filter !== 'all') {
            $historyQuery->where('outcome', $filter);
        }
        
        $callHistory = $historyQuery->paginate(15)->withQueryString();

        return view('dashboard.sales-calls.index', compact(
            'tab', 
            'filter', 
            'todayCalls', 
            'overdueCalls', 
            'callHistory', 
            'reachedCount', 
            'reachRate',
            'totalCalls',
            'hotLeadsCount',
            'followUpsCount',
            'serviceInterestsCount',
            'marketIntelCount'
        ));
    }

    /**
     * Show the full-page call logging form.
     */
    public function create()
    {
        $lead = null;
        if (request('lead_id')) {
            $lead = Lead::findOrFail(request('lead_id'));
        }

        return view('dashboard.sales-calls.create', compact('lead'));
    }

    /**
     * Store a newly created sales call.
     */
    public function store(Request $request)
    {
        // Persistence handled via specialized Outcome Logic (Action Layer)
        return redirect()->route('tyro-dashboard.sales-calls.index')
            ->with('success', 'Sales call session recorded successfully.');
    }

    public function show(SalesCall $salesCall)
    {
        return redirect()->route('tyro-dashboard.sales-calls.edit', $salesCall);
    }

    public function edit(SalesCall $salesCall)
    {
        return view('dashboard.sales-calls.create', [
            'lead' => $salesCall->lead,
            'call' => $salesCall,
        ]);
    }

    public function update(Request $request, SalesCall $salesCall)
    {
        return redirect()->route('tyro-dashboard.sales-calls.edit', $salesCall)
            ->with('success', 'Use the sales call form to update this record.');
    }

    public function destroy(SalesCall $salesCall)
    {
        $salesCall->delete();

        return redirect()->route('tyro-dashboard.sales-calls.index')
            ->with('success', 'Sales call deleted successfully.');
    }

    public function reopen(Lead $lead)
    {
        $lead->update([
            'status' => Lead::STATUS_ACTIVE,
            'close_reason' => null,
            'next_followup_at' => now(),
        ]);

        return redirect()->route('tyro-dashboard.sales-calls.create', ['lead_id' => $lead->id])
            ->with('success', 'Lead reopened for follow-up.');
    }
}
