<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\AuditLogService;
use App\Events\TicketCreated;
use Illuminate\Support\Str;

class TicketController extends Controller
{
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

        return response()->json([
            'reference_code' => $ticket->reference_code,
        ], 201);
    }

    public function show(string $referenceCode)
    {
        $ticket = Ticket::where('reference_code', $referenceCode)->firstOrFail();

        return new TicketResource($ticket);
    }

    private function uniqueReferenceCode(): string
    {
        do {
            $code = 'TKT-' . Str::upper(Str::random(8));
        } while (Ticket::where('reference_code', $code)->exists());

        return $code;
    }
}
