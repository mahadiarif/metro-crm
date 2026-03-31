<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignIndex extends Component
{
    use WithPagination;
    public $search;
    public $type;

    public function mount()
    {
        //
    }

    public function render()
    {
        $query = \App\Domains\Marketing\Models\Campaign::with('creator')
            ->withCount('recipients');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        $campaigns = $query->latest()->paginate(10);

        return view('livewire.marketing.campaign-index', [
            'campaigns' => $campaigns,
        ]);
    }

    public function export($format)
    {
        $query = \App\Domains\Marketing\Models\Campaign::with('creator')->withCount('recipients');
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $campaigns = $query->latest()->get();
        $filename = 'marketing_campaigns_' . date('Y-m-d');

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($campaigns) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
                fputcsv($file, ['Campaign Name', 'Type', 'Status', 'Recipients', 'Created By', 'Created At']);
                foreach ($campaigns as $c) {
                    fputcsv($file, [
                        $c->name,
                        strtoupper($c->type),
                        ucfirst($c->status),
                        $c->recipients_count,
                        $c->creator->name ?? 'N/A',
                        $c->created_at->format('Y-m-d H:i')
                    ]);
                }
                fclose($file);
            }, $filename . '.csv');
        }

        if ($format === 'excel') {
            return response()->streamDownload(function () use ($campaigns) {
                $file = fopen('php://output', 'w');
                fputs($file, "Campaign Name\tType\tStatus\tRecipients\tCreated By\tCreated At\n");
                foreach ($campaigns as $c) {
                    $row = [
                        $c->name,
                        strtoupper($c->type),
                        ucfirst($c->status),
                        $c->recipients_count,
                        $c->creator->name ?? 'N/A',
                        $c->created_at->format('Y-m-d H:i')
                    ];
                    fputs($file, implode("\t", $row) . "\n");
                }
                fclose($file);
            }, $filename . '.xls', ['Content-Type' => 'application/vnd.ms-excel']);
        }

        if ($format === 'pdf') {
            return redirect()->route('tyro-dashboard.reports.export.campaigns', [
                'search' => $this->search,
                'format' => 'pdf'
            ]);
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'type']);
    }

    public function deleteCampaign($id)
    {
        $campaign = \App\Domains\Marketing\Models\Campaign::findOrFail($id);
        $campaign->delete();
        session()->flash('message', 'Campaign intelligence successfully purged.');
    }

    public function duplicateCampaign($id)
    {
        $original = \App\Domains\Marketing\Models\Campaign::findOrFail($id);
        $new = $original->replicate();
        $new->name = $original->name . ' (Clone)';
        $new->status = 'draft';
        $new->created_at = now();
        $new->save();

        // Optionally replicate recipients if business logic demands
        
        return redirect()->route('tyro-dashboard.marketing.campaigns.create', ['clone_id' => $new->id]);
    }
}
