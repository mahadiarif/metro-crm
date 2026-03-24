<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Models\MarketingSetting;
use Livewire\Component;

class MarketingSettings extends Component
{
    public $email_driver = 'smtp';
    public $smtp_host;
    public $smtp_port = 587;
    public $smtp_username;
    public $smtp_password;
    public $smtp_encryption = 'tls';
    public $smtp_from_address;
    public $smtp_from_name;

    public $sms_driver = 'log'; // log, twilio, custom_sim
    public $twilio_sid;
    public $twilio_token;
    public $twilio_from_number;

    public $custom_sms_url;
    public $custom_sms_method = 'GET';
    public $custom_sms_api_key;
    public $custom_sms_api_key_param = 'api_key';
    public $custom_sms_number_param = 'to';
    public $custom_sms_message_param = 'message';
    public $custom_sms_sender_id;
    public $custom_sms_sender_id_param = 'sender_id';

    public $gennet_api_token;
    public $gennet_sid;
    public $gennet_domain;

    public function mount()
    {
        $this->email_driver = MarketingSetting::get('email_driver', 'smtp');
        $this->smtp_host = MarketingSetting::get('smtp_host');
        $this->smtp_port = MarketingSetting::get('smtp_port', 587);
        $this->smtp_username = MarketingSetting::get('smtp_username');
        $this->smtp_password = MarketingSetting::get('smtp_password');
        $this->smtp_encryption = MarketingSetting::get('smtp_encryption', 'tls');
        $this->smtp_from_address = MarketingSetting::get('smtp_from_address');
        $this->smtp_from_name = MarketingSetting::get('smtp_from_name');

        $this->sms_driver = MarketingSetting::get('sms_driver', 'log');
        $this->twilio_sid = MarketingSetting::get('twilio_sid');
        $this->twilio_token = MarketingSetting::get('twilio_token');
        $this->twilio_from_number = MarketingSetting::get('twilio_from_number');

        $this->custom_sms_url = MarketingSetting::get('custom_sms_url');
        $this->custom_sms_method = MarketingSetting::get('custom_sms_method', 'GET');
        $this->custom_sms_api_key = MarketingSetting::get('custom_sms_api_key');
        $this->custom_sms_api_key_param = MarketingSetting::get('custom_sms_api_key_param', 'api_key');
        $this->custom_sms_number_param = MarketingSetting::get('custom_sms_number_param', 'to');
        $this->custom_sms_message_param = MarketingSetting::get('custom_sms_message_param', 'message');
        $this->custom_sms_sender_id = MarketingSetting::get('custom_sms_sender_id');
        $this->custom_sms_sender_id_param = MarketingSetting::get('custom_sms_sender_id_param', 'sender_id');

        $this->gennet_api_token = MarketingSetting::get('gennet_api_token');
        $this->gennet_sid = MarketingSetting::get('gennet_sid');
        $this->gennet_domain = MarketingSetting::get('gennet_domain', 'https://api.gennet.com.bd');
    }

    public function saveSettings()
    {
        $settings = [
            'email_driver' => $this->email_driver,
            'smtp_host' => $this->smtp_host,
            'smtp_port' => $this->smtp_port,
            'smtp_username' => $this->smtp_username,
            'smtp_password' => $this->smtp_password,
            'smtp_encryption' => $this->smtp_encryption,
            'smtp_from_address' => $this->smtp_from_address,
            'smtp_from_name' => $this->smtp_from_name,

            'sms_driver' => $this->sms_driver,
            'twilio_sid' => $this->twilio_sid,
            'twilio_token' => $this->twilio_token,
            'twilio_from_number' => $this->twilio_from_number,

            'custom_sms_url' => $this->custom_sms_url,
            'custom_sms_method' => $this->custom_sms_method,
            'custom_sms_api_key' => $this->custom_sms_api_key,
            'custom_sms_api_key_param' => $this->custom_sms_api_key_param,
            'custom_sms_number_param' => $this->custom_sms_number_param,
            'custom_sms_message_param' => $this->custom_sms_message_param,
            'custom_sms_sender_id' => $this->custom_sms_sender_id,
            'custom_sms_sender_id_param' => $this->custom_sms_sender_id_param,

            'gennet_api_token' => $this->gennet_api_token,
            'gennet_sid' => $this->gennet_sid,
            'gennet_domain' => $this->gennet_domain,
        ];

        foreach ($settings as $key => $value) {
            MarketingSetting::set($key, $value, str_contains($key, 'sms') || str_contains($key, 'twilio') ? 'sms' : 'email');
        }

        session()->flash('success', 'Marketing settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.marketing.marketing-settings');
    }
}
