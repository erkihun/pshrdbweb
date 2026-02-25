<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->get('entity_type'));
        }

        if ($request->filled('from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('from'))->startOfDay());
        }

        if ($request->filled('to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('to'))->endOfDay());
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('metadata->reference_code', 'like', '%' . $search . '%')
                    ->orWhere('metadata->title', 'like', '%' . $search . '%')
                    ->orWhere('metadata->slug', 'like', '%' . $search . '%');
            });
        }

        $auditLogs = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get();
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $entityTypes = AuditLog::select('entity_type')->distinct()->orderBy('entity_type')->pluck('entity_type');

        return view('admin.audit-logs.index', compact('auditLogs', 'users', 'actions', 'entityTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
