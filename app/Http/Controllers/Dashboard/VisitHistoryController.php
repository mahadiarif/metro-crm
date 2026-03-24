<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitHistoryController extends Controller
{
    public function getHistory(Lead $lead)
    {
        $visits = Visit::where('lead_id', $lead->id)
            ->with('user')
            ->orderBy('visit_date', 'desc')
            ->take(5)
            ->get();

        if ($visits->isEmpty()) {
            return response()->json([
                'html' => '<div class="text-sm text-muted-foreground p-3 border border-dashed rounded-lg">No previous visits found for this lead.</div>'
            ]);
        }

        $html = '<div class="visit-history-preview space-y-3">';
        foreach ($visits as $visit) {
            $date = $visit->visit_date ? $visit->visit_date->format('d M, Y') : 'N/A';
            $stage = $visit->visit_stage ?? 'N/A';
            $executive = $visit->user->name ?? 'Unknown';
            $notes = $visit->meeting_notes ? (strlen($visit->meeting_notes) > 100 ? substr($visit->meeting_notes, 0, 100) . '...' : $visit->meeting_notes) : 'No notes.';

            $html .= "
                <div class='p-3 rounded-lg border bg-card text-card-foreground shadow-sm' style='background: rgba(255,255,255,0.05); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.1);'>
                    <div class='flex justify-between items-start mb-1'>
                        <span class='text-xs font-semibold text-primary'>$stage</span>
                        <span class='text-[10px] text-muted-foreground'>$date</span>
                    </div>
                    <p class='text-xs text-foreground mb-1'>$notes</p>
                    <div class='text-[10px] text-muted-foreground italic'>By: $executive</div>
                </div>";
        }
        $html .= '</div>';

        return response()->json([
            'html' => $html
        ]);
    }
}
