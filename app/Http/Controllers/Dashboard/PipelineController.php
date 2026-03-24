<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PipelineStage;
use App\Models\Lead;
use App\Domains\Leads\Actions\MoveLeadToStageAction;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function kanban()
    {
        $stages = PipelineStage::with(['leads' => function($query) {
            $query->with(['service', 'assignedUser'])->orderBy('updated_at', 'desc');
        }])->orderBy('order_column')->get();

        return view('vendor.tyro-dashboard.pipelines.kanban', compact('stages'));
    }

    public function updateStage(Request $request, MoveLeadToStageAction $moveAction)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'stage_id' => 'required|exists:pipeline_stages,id',
        ]);

        $success = $moveAction->execute($request->lead_id, $request->stage_id);

        return response()->json(['success' => $success]);
    }
}
