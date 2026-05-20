<?php

/*namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

   
    public function __construct()
    {
        //
    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dynamic Mail',
        );
    }

   
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    
    public function attachments(): array
    {
        return [];
    }
}*/



namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyContent;

    public function __construct($subjectText, $bodyContent)
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.dynamic');
    }
}
