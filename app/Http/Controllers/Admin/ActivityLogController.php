<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Filter by admin status
        if ($request->has('filter') && $request->filter !== '') {
            if ($request->filter === 'admin') {
                $query->admins();
            } elseif ($request->filter === 'regular') {
                $query->regular();
            }
        }

        $activityLogs = $query->latest()->paginate(10);

        return view('admin.activity_logs.index', compact('activityLogs'));
    }
}
