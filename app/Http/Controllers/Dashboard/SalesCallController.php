<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\SalesCall;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesCallController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'today');

        // Base query for leads accessible to the user
        $baseLeads = Lead::accessible();

        // 1. Today's Calls: next_call_at is today OR no call was ever made (newly assigned)
        $todayCalls = (clone $baseLeads)
            ->where(function ($q) {
                $q->whereDate('leads.created_at', Carbon::today())
                  ->orWhereHas('salesCalls', function ($sq) {
                      $sq->whereDate('next_call_at', Carbon::today());
                  })
                  ->orWhereDoesntHave('salesCalls');
            })
            ->with(['latestCall'])
            ->get();

        // 2. Overdue Calls: next_call_at < now() AND outcome was busy/no_answer
        $overdueCalls = (clone $baseLeads)
            ->whereHas('salesCalls', function ($q) {
                $q->where('next_call_at', '<', now())
                  ->whereIn('outcome', ['busy', 'no_answer', 'follow_up']);
            })
            ->with(['latestCall'])
            ->get();

        // 3. All Calls History (for stats and log)
        $historyQuery = SalesCall::with(['lead', 'user'])
            ->orderBy('called_at', 'desc');
            
        $historyCalls = (clone $historyQuery)->paginate(20);
        
        // 4. Success Metrics (for the cards)
        $answeredCount     = (clone $historyQuery)->whereIn('outcome', ['reached', 'answered'])->count();
        $totalCallsCount   = (clone $historyQuery)->count();
        $answerRate        = $totalCallsCount > 0 ? round(($answeredCount / $totalCallsCount) * 100, 1) : 0;

        return view('dashboard.sales-calls.index', compact(
            'todayCalls', 'overdueCalls', 'historyCalls', 'tab', 'answeredCount', 'answerRate'
        ));
    }
}
