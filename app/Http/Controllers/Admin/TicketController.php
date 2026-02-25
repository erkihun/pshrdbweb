<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTicketRequest;
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

        $tickets = $query->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('repliedBy');

        return view('admin.tickets.show', compact('ticket'));
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

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', __('common.messages.ticket_updated'));
    }

    public function destroy(Ticket $ticket)
    {
        AuditLogService::log('tickets.deleted', 'ticket', $ticket->id, [
            'reference_code' => $ticket->reference_code,
            'subject' => $ticket->subject,
        ]);

        $ticket->delete();

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', __('common.messages.ticket_deleted'));
    }
}
