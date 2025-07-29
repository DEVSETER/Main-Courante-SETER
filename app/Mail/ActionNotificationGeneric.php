<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActionNotificationGeneric extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $action;
    public $message;
    public $type;
    public $email;
    public $eventUrl;
    public function __construct($action, $message, $type, $email)
    {
        $this->action = $action;
        $this->message = $message;
        $this->type = $type;
        $this->email = $email;
        $this->eventUrl = url('/evenements/' . $action->evenement_id); // Génère le lien

    }
public function build()
    {
        $subject = match($this->type) {
            'demande_validation' => '[Main Courante] Demande de validation',
            'aviser' => '[Main Courante] Avis important',
            'informer' => '[Main Courante] Information',
            default => '[Main Courante] Notification'
        };

        return $this->subject($subject)
                   ->view('emails.action-notification-generic')
                   ->with([
                       'action' => $this->action,
                       'message' => $this->message,
                       'type' => $this->type,
                       'email' => $this->email,
                       'evenement' => $this->action->evenement
                   ]);
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Notification Generic',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
