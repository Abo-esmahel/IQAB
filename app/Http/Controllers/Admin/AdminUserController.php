<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('numberPurchases.phoneNumber');

        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user)
    {
        $this->authorize('suspend', $user);
        $old = $user->status->value;
        $user->update(['status' => 'suspended']);
        $this->logAudit(AuditAction::Suspend, $user, ['status' => $old], ['status' => 'suspended'], "Suspended user {$user->email}");
        return back()->with('success', 'User suspended.');
    }

    public function activate(User $user)
    {
        $this->authorize('activate', $user);
        $old = $user->status->value;
        $user->update(['status' => 'active']);
        $this->logAudit(AuditAction::Activate, $user, ['status' => $old], ['status' => 'active'], "Activated user {$user->email}");
        return back()->with('success', 'User activated.');
    }

    protected function logAudit(AuditAction $action, User $user, array $old, array $new, string $description): void
    {
        AuditLog::create([
            'user_id' => request()->user()?->id,
            'auditable_type' => $user->getMorphClass(),
            'auditable_id' => $user->id,
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
