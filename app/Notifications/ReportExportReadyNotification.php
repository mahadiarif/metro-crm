<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportExportReadyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $filename,
        protected string $downloadUrl
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
                    ->subject('Your Report Export is Ready')
                    ->line('The report export you requested has been generated and is ready for download.')
                    ->line('File: ' . $this->filename)
                    ->action('Download Report', $this->downloadUrl)
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
            'filename' => $this->filename,
            'download_url' => $this->downloadUrl,
            'message' => 'Your report "' . $this->filename . '" is ready for download.',
        ];
    }
}
