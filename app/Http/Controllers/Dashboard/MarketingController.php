<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Domains\Marketing\Models\Campaign;
use App\Domains\Marketing\Models\CampaignRecipient;
use App\Domains\Marketing\Models\MarketingTemplate;
use App\Models\Lead;
use App\Jobs\SendCampaignMailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount('recipients')->latest()->paginate(10);
        return view('vendor.tyro-dashboard.marketing.index', compact('campaigns'));
    }

    public function create()
    {
        $templates = MarketingTemplate::all();
        return view('vendor.tyro-dashboard.marketing.create', compact('templates'));
    }

    public function show(Campaign $campaign)
    {
        $recipients = $campaign->recipients()->with('lead')->paginate(20);
        return view('vendor.tyro-dashboard.marketing.show', compact('campaign', 'recipients'));
    }

    /**
     * Bulk add recipients to a campaign based on filters.
     */
    public function addRecipients(Request $request, Campaign $campaign)
    {
        $query = Lead::subscribed(); // Exclude unsubscribed

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }
        if ($request->filled('assigned_user')) {
            $query->where('assigned_user', $request->assigned_user);
        }

        $leads = $query->get();
        $addedCount = 0;

        foreach ($leads as $lead) {
            $recipient = CampaignRecipient::firstOrCreate([
                'campaign_id' => $campaign->id,
                'lead_id'     => $lead->id,
            ], [
                'email'  => $lead->email,
                'phone'  => $lead->phone,
                'status' => CampaignRecipient::STATUS_PENDING,
            ]);

            if ($recipient->wasRecentlyCreated) {
                $addedCount++;
            }
        }

        return back()->with('success', "$addedCount new recipients added to campaign.");
    }

    /**
     * Send campaign emails using background queue.
     */
    public function send(Campaign $campaign)
    {
        $template = MarketingTemplate::find($campaign->template_id);
        if (!$template) {
            return back()->with('error', 'Campaign template not found.');
        }

        $campaign->update(['status' => Campaign::STATUS_SENDING]);

        $pendingRecipients = $campaign->recipients()
            ->where('status', CampaignRecipient::STATUS_PENDING)
            ->get();

        foreach ($pendingRecipients as $recipient) {
            $renderedContent = $template->renderFor($recipient->lead);
            SendCampaignMailJob::dispatch($recipient, $campaign->name, $renderedContent);
        }

        return back()->with('success', 'Campaign sending started in the background.');
    }

    /**
     * Retry failed recipients.
     */
    public function retry(Campaign $campaign)
    {
        $template = MarketingTemplate::find($campaign->template_id);
        
        $failedRecipients = $campaign->recipients()
            ->where('status', CampaignRecipient::STATUS_FAILED)
            ->get();

        foreach ($failedRecipients as $recipient) {
            $renderedContent = $template->renderFor($recipient->lead);
            SendCampaignMailJob::dispatch($recipient, $campaign->name, $renderedContent);
            $recipient->update(['status' => CampaignRecipient::STATUS_PENDING, 'error_message' => null]);
        }

        return back()->with('success', "Retrying " . $failedRecipients->count() . " failed recipients.");
    }

    /**
     * Preview template rendering with a sample lead.
     */
    public function previewTemplate(MarketingTemplate $template)
    {
        $sampleLead = Lead::first() ?? new Lead([
            'client_name'  => 'Sample Client',
            'company_name' => 'Sample Company',
            'phone'        => '01700000000',
            'email'        => 'sample@example.com',
        ]);

        return response()->json([
            'subject' => "Preview Mode",
            'content' => $template->renderFor($sampleLead),
        ]);
    }

    public function templates()
    {
        $templates = MarketingTemplate::with('creator')->latest()->paginate(10);
        return view('vendor.tyro-dashboard.marketing.templates', compact('templates'));
    }

    public function settings()
    {
        return view('vendor.tyro-dashboard.marketing.settings');
    }
}
