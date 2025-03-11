<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(AuditRequest $request)
    {
        $query = AuditLog::with('user:id,name'); // Load only the 'id' and 'name' fields

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('ip_id')) {
            $query->where('ip_id', $request->ip_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } else {
            $query->whereDate('created_at', '>=', Carbon::today()->toDateString());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        } else {
            $query->whereDate('created_at', '<=', Carbon::today()->toDateString());
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'username' => $log->user->name ?? 'Unknown', // Use 'name' as 'username'
                    'action' => $log->action,
                    'ip_id' => $log->ip_id,
                    'changes' => json_decode($log->changes, true) ?? [],
                    'ip_address' => $log->ip_address,
                    'created_at' => $log->created_at,
                ];
            });

        return response()->json($auditLogs);
    }
}
