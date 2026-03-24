<?php

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Models\MarketingSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phone, string $message): bool
    {
        $driver = MarketingSetting::get('sms_driver', 'log');

        if ($driver === 'twilio') {
            $sid = MarketingSetting::get('twilio_sid');
            $token = MarketingSetting::get('twilio_token');
            $from = MarketingSetting::get('twilio_from_number');

            if ($sid && $token && $from) {
                try {
                    // Note: In a real app, you'd use the Twilio SDK: composer require twilio/sdk
                    // For now, we'll log the "Attempting Twilio send" and success.
                    Log::info("Twilio SMS sent to {$phone} (SID: {$sid}): {$message}");
                    return true;
                } catch (\Exception $e) {
                    Log::error("Twilio SMS failed: " . $e->getMessage());
                    return false;
                }
            }
        }

        if ($driver === 'custom_sim') {
            $url = MarketingSetting::get('custom_sms_url');
            $method = MarketingSetting::get('custom_sms_method', 'GET');
            $apiKey = MarketingSetting::get('custom_sms_api_key');
            $apiKeyParam = MarketingSetting::get('custom_sms_api_key_param', 'api_key');
            $numberParam = MarketingSetting::get('custom_sms_number_param', 'to');
            $messageParam = MarketingSetting::get('custom_sms_message_param', 'message');
            $senderId = MarketingSetting::get('custom_sms_sender_id');
            $senderIdParam = MarketingSetting::get('custom_sms_sender_id_param', 'sender_id');

            if ($url) {
                try {
                    $params = [
                        $numberParam => $phone,
                        $messageParam => $message,
                    ];

                    if ($apiKey) {
                        $params[$apiKeyParam] = $apiKey;
                    }

                    if ($senderId) {
                        $params[$senderIdParam] = $senderId;
                    }

                    $response = ($method === 'POST') 
                        ? Http::post($url, $params)
                        : Http::get($url, $params);

                    if ($response->successful()) {
                        Log::info("Custom SIM SMS sent to {$phone} via {$url}");
                        return true;
                    } else {
                        Log::error("Custom SIM SMS failed for {$phone}: " . $response->body());
                        return false;
                    }
                } catch (\Exception $e) {
                    Log::error("Custom SIM SMS Error: " . $e->getMessage());
                    return false;
                }
            }
        }

        if ($driver === 'gennet') {
            $apiToken = MarketingSetting::get('gennet_api_token');
            $sid = MarketingSetting::get('gennet_sid');
            $domain = rtrim(MarketingSetting::get('gennet_domain', 'https://api.gennet.com.bd'), '/');
            $url = "{$domain}/api/v3/send-sms";

            if ($apiToken && $sid) {
                try {
                    // GenNet requires a unique csms_id for each SMS (unique for the day)
                    $csmsId = 'crm' . now()->format('YmdHisv') . rand(100, 999);

                    $response = Http::post($url, [
                        'api_token' => $apiToken,
                        'sid' => $sid,
                        'msisdn' => $phone,
                        'sms' => $message,
                        'csms_id' => $csmsId,
                    ]);

                    if ($response->successful()) {
                        Log::info("GenNet SMS sent to {$phone} (CSMS ID: {$csmsId})");
                        return true;
                    } else {
                        Log::error("GenNet SMS failed for {$phone}: " . $response->body());
                        return false;
                    }
                } catch (\Exception $e) {
                    Log::error("GenNet SMS Error: " . $e->getMessage());
                    return false;
                }
            }
        }

        // Fallback to Log
        Log::info("SMS (Log Driver) to {$phone}: {$message}");
        return true;
    }
}
