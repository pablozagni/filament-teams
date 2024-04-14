<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct( protected $data)
    {
    }

    public function envelope(): Envelope
    {
        $team_id = $this->data['team_id'];
        $teamname = Team::find($team_id)->name;
        return new Envelope(
            subject: 'Invitation to join ' . $teamname,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.invitation',
            with: [
                'acceptUrl' => URL::route( "invitation.accept", $this->data['token'] ),
                'rejectUrl' => URL::route( "invitation.reject", $this->data['token'] ),
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
