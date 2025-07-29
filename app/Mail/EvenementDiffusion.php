<?php

namespace App\Mail;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementDiffusion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     public $evenement;
    public $messagePersonnalise;
    public $expediteur;
    public $inclureActions;
    public $inclureCommentaires;
    public $eventUrl;
   public function __construct(
        Evenement $evenement,
        $messagePersonnalise = null,
        User $expediteur = null,
        $inclureActions = true,
        $inclureCommentaires = true
    ) {
        $this->evenement = $evenement;
        $this->messagePersonnalise = $messagePersonnalise;
        $this->expediteur = $expediteur;
        $this->inclureActions = $inclureActions;
        $this->inclureCommentaires = $inclureCommentaires;
        $this->eventUrl = url('/evenements'); // Génère le lien

    }

    public function build()
    {
        $subject = "📋 Événement #{$this->evenement->id} - " . ($this->evenement->nature_evenement->libelle ?? 'Événement');

        return $this->subject($subject)
                    ->view('emails.evenement-diffusion')
                    ->with([
                        'evenement' => $this->evenement,
                        'messagePersonnalise' => $this->messagePersonnalise,
                        'expediteur' => $this->expediteur,
                        'inclureActions' => $this->inclureActions,
                        'inclureCommentaires' => $this->inclureCommentaires
                    ]);
    }
}
