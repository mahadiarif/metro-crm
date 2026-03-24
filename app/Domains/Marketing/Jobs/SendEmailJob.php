<?php

namespace App\Domains\Marketing\Jobs;

use App\Domains\Marketing\Models\CampaignRecipient;
use App\Domains\Marketing\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected CampaignRecipient $recipient,
        protected string $subject,
        protected string $message
    ) {}

    public function handle(EmailService $emailService): void
    {
        $status = $emailService->send(
            $this->recipient->email,
            $this->subject,
            $this->message
        );

        if ($status) {
            $this->recipient->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } else {
            $this->recipient->update(['status' => 'failed']);
        }
    }
}
