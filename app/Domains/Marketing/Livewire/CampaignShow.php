<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignShow extends Component
{
    use WithPagination;


    public Campaign $campaign;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign->load(['creator']);
    }

    public function render()
    {
        $recipients = $this->campaign->recipients()
            ->with('lead')
            ->paginate(15);

        // Fetch stats in a single query to avoid multiple counts
        $counts = $this->campaign->recipients()
            ->selectRaw("
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'sent' THEN 1 END) as sent,
                COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending
            ")
            ->first();

        return view('livewire.marketing.campaign-show', [
            'recipients' => $recipients,
            'stats' => [
                'total' => $counts?->total ?? 0,
                'sent' => $counts?->sent ?? 0,
                'failed' => $counts?->failed ?? 0,
                'pending' => $counts?->pending ?? 0,
            ]
        ]);
    }

    public function duplicateCampaign($id)
    {
        $original = \App\Domains\Marketing\Models\Campaign::findOrFail($id);
        $new = $original->replicate();
        $new->name = $original->name . ' (Clone)';
        $new->status = 'draft';
        $new->created_at = now();
        $new->save();

        return redirect()->route('tyro-dashboard.marketing.campaigns.create', ['clone_id' => $new->id]);
    }
}
