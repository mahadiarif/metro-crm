<?php

namespace App\Domains\Marketing\Jobs;

use App\Domains\Marketing\Models\CampaignRecipient;
use App\Domains\Marketing\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected CampaignRecipient $recipient,
        protected string $message
    ) {}

    public function handle(SmsService $smsService): void
    {
        $status = $smsService->send($this->recipient->phone, $this->message);

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
