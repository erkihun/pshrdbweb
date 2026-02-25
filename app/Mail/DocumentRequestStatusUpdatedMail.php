<?php

namespace App\Mail;

use App\Models\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestStatusUpdatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public DocumentRequest $documentRequest)
    {
    }

    public function build(): DocumentRequestStatusUpdatedMail
    {
        return $this->subject(__('Document Request Status Updated'))
            ->markdown('emails.document-request-status', [
                'request' => $this->documentRequest,
            ]);
    }
}
