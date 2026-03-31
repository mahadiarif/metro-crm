<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Lead $lead,
        protected User $assigner
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Lead Assigned: ' . $this->lead->company_name)
                    ->line('You have been assigned a new lead by ' . $this->assigner->name . '.')
                    ->line('Lead: ' . $this->lead->company_name)
                    ->line('Client: ' . $this->lead->client_name)
                    ->action('View Lead', route('tyro-dashboard.leads.show', $this->lead->id))
                    ->line('Thank you for using our CRM!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'company_name' => $this->lead->company_name,
            'assigned_by' => $this->assigner->name,
            'message' => 'You have been assigned a new lead: ' . $this->lead->company_name,
        ];
    }
}
