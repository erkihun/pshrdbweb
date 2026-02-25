<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Ticket;
use App\Services\AuditLogService;
use App\Events\TicketCreated;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.index');
    }

    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();
        $data['reference_code'] = $this->uniqueReferenceCode();
        $data['status'] = 'open';

        $ticket = Ticket::create($data);

        AuditLogService::log('tickets.created', 'ticket', $ticket->id, [
            'reference_code' => $ticket->reference_code,
            'subject' => $ticket->subject,
            'email' => $ticket->email,
        ]);

        TicketCreated::dispatch($ticket);

        return redirect()
            ->route('contact')
            ->with('success', __('common.messages.contact_success'));
    }

    private function uniqueReferenceCode(): string
    {
        do {
            $code = 'TKT-' . Str::upper(Str::random(8));
        } while (Ticket::where('reference_code', $code)->exists());

        return $code;
    }
}
