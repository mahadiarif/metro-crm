<?php

namespace App\Domains\Marketing\Services;

class EmailService
{
    public function send(string $email, string $subject, string $message): bool
    {
        $host = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_host');
        $port = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_port');
        $username = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_username');
        $password = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_password');
        $encryption = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_encryption', 'tls');
        $fromEmail = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_from_address');
        $fromName = \App\Domains\Marketing\Models\MarketingSetting::get('smtp_from_name');

        if ($host && $username && $password) {
            try {
                // Dynamically configure mailer
                config([
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => $port,
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $password,
                    'mail.mailers.smtp.encryption' => $encryption,
                    'mail.from.address' => $fromEmail,
                    'mail.from.name' => $fromName,
                ]);

                \Illuminate\Support\Facades\Mail::raw($message, function ($msg) use ($email, $subject) {
                    $msg->to($email)->subject($subject);
                });

                return true;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Custom SMTP Email failed: " . $e->getMessage());
                return false;
            }
        }

        // Integration with Email Service (e.g., Mailgun, SES, SMTP)
        \Illuminate\Support\Facades\Log::info("Sending Email (Log Driver) to {$email} with subject: {$subject}");
        return true;
    }
}
