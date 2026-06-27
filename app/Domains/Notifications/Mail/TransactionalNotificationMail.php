<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Domains\Notifications\Models\Notification;

class TransactionalNotificationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Notification $notification
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: (string) ($this->notification->subject ?: $this->notification->title)
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'notification' => $this->notification,
            ]
        );
    }
}
