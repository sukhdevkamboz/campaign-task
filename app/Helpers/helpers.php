<?php

use App\Models\ActivityLog;

function activityLog($module, $action, $description = null)
{
    ActivityLog::create([
        'user_id' => auth()->id(),
        'module' => $module,
        'action' => $action,
        'description' => $description,
        'ip_address' => request()->ip(),
    ]);
}