<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class AdminWebhookLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WebhookLog::query();

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.webhooks.index', compact('logs'));
    }

    public function show(WebhookLog $log)
    {
        return view('admin.webhooks.show', ['webhook' => $log]);
    }
}
