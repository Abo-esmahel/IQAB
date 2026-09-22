<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\AuditAction;
use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Models\AuditLog;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NumberAssignedMail;
use App\Mail\WelcomeMail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'string', 'in:user,admin'],
            'status' => ['required', 'string', 'in:active,suspended'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        $this->logAudit(AuditAction::Create, $user, [], $user->toArray(), "Created user {$user->email} with role {$user->role}");

        Mail::to($user)->queue(new WelcomeMail($user, $validated['password']));

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->email} created ({$user->role}).");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'string', 'in:user,admin'],
            'status' => ['required', 'string', 'in:active,suspended'],
        ]);

        $isSelf = $user->id === $request->user()->id;

        if ($isSelf && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove your own admin role.'])->withInput();
        }

        if ($isSelf && $validated['status'] !== 'active') {
            return back()->withErrors(['status' => 'You cannot suspend your own account.'])->withInput();
        }

        $old = $user->toArray();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $this->logAudit(AuditAction::Update, $user, $old, $user->toArray(), "Updated user {$user->email}");

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
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

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $email = $user->email;
        $user->delete();

        AuditLog::create([
            'user_id' => request()->user()?->id,
            'auditable_type' => $user->getMorphClass(),
            'auditable_id' => $user->id,
            'action' => AuditAction::Delete,
            'old_values' => ['email' => $email],
            'new_values' => [],
            'description' => "Deleted user {$email}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.users.index')->with('success', "User {$email} deleted.");
    }

    public function assignNumber(Request $request, User $user)
    {
        $request->validate([
            'phone_number_id' => ['required', 'exists:phone_numbers,id'],
        ]);

        $phoneNumber = PhoneNumber::findOrFail($request->phone_number_id);

        $purchase = NumberPurchase::create([
            'user_id' => $user->id,
            'phone_number_id' => $phoneNumber->id,
            'price' => $phoneNumber->price,
            'status' => NumberPurchaseStatus::Active->value,
            'purchased_at' => now(),
            'expires_at' => $phoneNumber->expires_at ?? now()->addDays((int) (\App\Models\Setting::get('system.number_expiry_days', 30))),
        ]);

        // Mark number as active (assigned) if it was available
        if ($phoneNumber->status === PhoneNumberStatus::Available) {
            $phoneNumber->update(['status' => PhoneNumberStatus::Active->value]);
        }

        AuditLog::create([
            'user_id' => request()->user()?->id,
            'auditable_type' => $purchase->getMorphClass(),
            'auditable_id' => $purchase->id,
            'action' => AuditAction::Create,
            'old_values' => [],
            'new_values' => $purchase->toArray(),
            'description' => "Assigned number {$phoneNumber->phone_number} to user {$user->email}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Mail::to($user)->queue(new NumberAssignedMail($user, $purchase));

        return back()->with('success', "Number {$phoneNumber->phone_number} assigned to {$user->name}. It now appears in their numbers.");
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
