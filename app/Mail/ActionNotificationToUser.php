<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActionNotificationToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $action;
    public $message;
    public $type;
    public $user;
    public $eventUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($action, $message, $type, $user)
    {
        $this->action = $action;
        $this->message = $message;
        $this->type = $type;
        $this->user = $user;
        $this->eventUrl = route('evenements.index', ['highlight' => $action->evenement_id]);

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'demande_validation' => '[Main Courante] Demande de validation',
            'aviser' => '[Main Courante] Avis important',
            'informer' => '[Main Courante] Information',
            default => '[Main Courante] Notification'
        };

        Log::info('Construction de l\'email', [
            'sujet' => $subject,
            'type' => $this->type,
        ]);

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
            */public function content(): Content
        {
            return new Content(
                view: 'emails.action-notification-user',
                with: [
                    'action' => $this->action,
                    'messageContent' => $this->message,
                    'type' => $this->type,
                    'user' => $this->user,
                    'evenement' => $this->action->evenement ?? null
                ],
            );
        }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
