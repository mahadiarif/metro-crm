<?php

namespace App\Jobs;

use App\Domains\Marketing\Models\CampaignRecipient;
use App\Mail\SendCampaignMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected CampaignRecipient $recipient,
        protected string $subject,
        protected string $renderedContent
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipient->email)->send(
                new SendCampaignMail($this->subject, $this->renderedContent)
            );

            $this->recipient->update([
                'status' => CampaignRecipient::STATUS_SENT,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->recipient->update([
                'status' => CampaignRecipient::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
