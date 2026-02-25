<?php

namespace App\Mail;

use App\Models\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestSubmittedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public DocumentRequest $documentRequest)
    {
    }

    public function build(): DocumentRequestSubmittedMail
    {
        return $this->subject(__('Document Request Received'))
            ->markdown('emails.document-request-submitted', [
                'request' => $this->documentRequest,
            ]);
    }
}
