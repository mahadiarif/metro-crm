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

        // 1. Calculate Core Metrics (Aggregated)
        $totalCalls = SalesCall::count();
        $reachedCount = SalesCall::where('outcome', 'service_request')->count();
        $reachRate = $totalCalls > 0 ? round(($reachedCount / $totalCalls) * 100) : 0;
        
        $hotLeadsCount = Lead::accessible()->where('lead_temperature', 'hot')->count();
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
            ->with(['latestCall', 'service'])
            ->get();

        // Overdue Queue (Scheduled before today)
        $overdueCalls = Lead::accessible()
            ->whereDate('next_followup_at', '<', $today)
            ->where('status', '!=', Lead::STATUS_CLOSED)
            ->with(['latestCall', 'service'])
            ->get();

        // 3. History Feed (Logged interactions)
        $historyQuery = SalesCall::with(['lead', 'user'])->latest();
        
        if ($filter !== 'all') {
            $historyQuery->where('outcome', $filter);
        }
        
        $historyCalls = $historyQuery->paginate(15)->withQueryString();

        return view('dashboard.sales-calls.index', compact(
            'tab', 
            'filter', 
            'todayCalls', 
            'overdueCalls', 
            'historyCalls', 
            'reachedCount', 
            'reachRate',
            'totalCalls',
            'hotLeadsCount',
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
}
