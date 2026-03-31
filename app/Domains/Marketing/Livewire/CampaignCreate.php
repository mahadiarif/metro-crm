<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateCampaignAction;
use App\Domains\Marketing\Actions\SendEmailCampaignAction;
use App\Domains\Marketing\Actions\SendSmsCampaignAction;
use App\Domains\Marketing\DTO\CampaignData;
use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\Service;
use App\Models\User;
use Livewire\Component;

class CampaignCreate extends Component
{
    public $name;
    public $type = 'email';
    public $campaign_message = '';
    
    // Filters
    public $stageId;
    public $serviceId;
    public $assignedUser;
    public $fromDate;
    public $toDate;

    // Template Modal
    public $showTemplateModal = false;
    public $newTemplateName = '';
    public $newTemplateContent = '';

    public $templateId;
    public $recipientCount = 0;
    public $sampleRecipients = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:sms,email',
        'campaign_message' => 'required|string',
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['stageId', 'serviceId', 'assignedUser', 'fromDate', 'toDate'])) {
            $this->updateRecipientCount();
        }

        if ($propertyName === 'templateId' && $this->templateId) {
            $this->applyTemplate();
        }
    }

    public function applyTemplate()
    {
        $template = \App\Domains\Marketing\Models\MarketingTemplate::find($this->templateId);
        if ($template) {
            $this->campaign_message = $template->content;
        }
    }

    public function mount()
    {
        $this->updateRecipientCount();
    }

    public function updateRecipientCount()
    {
        $query = $this->getFilteredLeadsQuery();
        $this->recipientCount = $query->count();
        $this->sampleRecipients = $query->limit(5)->get();
    }

    protected function getFilteredLeadsQuery()
    {
        $query = Lead::accessible();

        if ($this->stageId) {
            $query->where('stage_id', $this->stageId);
        }

        if ($this->serviceId) {
            $query->where('service_id', $this->serviceId);
        }

        if ($this->assignedUser) {
            $query->where('assigned_user', $this->assignedUser);
        }

        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        return $query;
    }

    public function openTemplateModal()
    {
        $this->newTemplateName = '';
        $this->newTemplateContent = $this->campaign_message;
        $this->showTemplateModal = true;
    }

    public function closeTemplateModal()
    {
        $this->showTemplateModal = false;
    }

    public function saveTemplate()
    {
        $this->validate([
            'newTemplateName' => 'required|string|max:255',
            'newTemplateContent' => 'required|string',
        ]);

        $template = \App\Domains\Marketing\Models\MarketingTemplate::create([
            'name' => $this->newTemplateName,
            'content' => $this->newTemplateContent,
            'type' => $this->type,
        ]);

        $this->templateId = $template->id;
        $this->campaign_message = $template->content;
        $this->showTemplateModal = false;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Template created successfully.'
        ]);
    }

    public function createCampaign(CreateCampaignAction $createAction, SendSmsCampaignAction $smsAction, SendEmailCampaignAction $emailAction)
    {
        $this->validate();

        $recipients = $this->getFilteredLeadsQuery()->get();

        if ($recipients->isEmpty()) {
            $this->addError('recipients', 'No leads found with selected filters.');
            return;
        }

        $campaignData = CampaignData::fromArray([
            'name' => $this->name,
            'type' => $this->type,
            'message' => $this->campaign_message,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        $campaign = $createAction->execute($campaignData, $recipients);

        if ($this->type === 'sms') {
            $smsAction->execute($campaign);
        } else {
            $emailAction->execute($campaign);
        }

        session()->flash('success', 'Campaign created and queued for sending.');
        return redirect()->route('tyro-dashboard.marketing.campaigns.index');
    }

    public function render()
    {
        return view('livewire.marketing.campaign-create', [
            'stages' => PipelineStage::orderBy('order_column')->get(),
            'services' => Service::orderBy('name')->get(),
            'users' => User::whereHas('leads')->orderBy('name')->get(),
            'templates' => \App\Domains\Marketing\Models\MarketingTemplate::where('type', $this->type)->orderBy('name')->get(),
        ]);
    }
}
