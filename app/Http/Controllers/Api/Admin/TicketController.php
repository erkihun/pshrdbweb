<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\AuditLogService;
use App\Events\TicketReplied;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        return TicketResource::collection($query->paginate(15));
    }

    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();
        $statusChanged = $ticket->status !== $data['status'];
        $replyAdded = isset($data['admin_reply']) && $data['admin_reply'] !== '';

        $ticket->status = $data['status'];
        $ticket->admin_reply = $data['admin_reply'] ?? null;

        if ($replyAdded) {
            $ticket->replied_at = now();
            $ticket->replied_by = $request->user()->id;
        }

        $ticket->save();

        if ($statusChanged) {
            AuditLogService::log('tickets.status_changed', 'ticket', $ticket->id, [
                'reference_code' => $ticket->reference_code,
                'status' => $ticket->status,
            ]);
        }

        if ($replyAdded) {
            AuditLogService::log('tickets.replied', 'ticket', $ticket->id, [
                'reference_code' => $ticket->reference_code,
                'status' => $ticket->status,
            ]);

            TicketReplied::dispatch($ticket);
        }

        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        AuditLogService::log('tickets.deleted', 'ticket', $ticket->id, [
            'reference_code' => $ticket->reference_code,
            'subject' => $ticket->subject,
        ]);

        $ticket->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
