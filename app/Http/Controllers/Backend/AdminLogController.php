<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    /**
     * Display a listing of admin logs with filters.
     */
    public function index(Request $request)
    {
        $query = AdminLog::with('admin');

        // Filter by Search Query (Admin Name, Action, Description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('admin_name', 'like', "%{$search}%");
            });
        }

        // Filter by Action Type
        if ($request->filled('action_type')) {
            $query->where('action', $request->action_type);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Paginate logs, ordering by newest first
        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Fetch distinct actions dynamically to build the filter dropdown
        $actionTypes = AdminLog::distinct()->orderBy('action')->pluck('action');

        return view('backend.pages.settings.logs', compact('logs', 'actionTypes'));
    }
}
