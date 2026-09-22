<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AuditAction;
use App\Enums\PhoneNumberStatus;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePhoneNumberRequest;
use App\Http\Requests\Admin\UpdatePhoneNumberRequest;
use App\Models\AuditLog;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;

class AdminNumberController extends Controller
{
    use HandlesImageUploads;
    public function index(Request $request)
    {
        $query = PhoneNumber::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->search . '%')
                  ->orWhere('provider_number_id', 'like', '%' . $request->search . '%');
            });
        }

        $numbers = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $countries = PhoneNumber::distinct()->pluck('country')->filter()->values();

        return view('admin.numbers.index', compact('numbers', 'countries'));
    }

    public function create()
    {
        $statuses = PhoneNumberStatus::cases();

        return view('admin.numbers.create', compact('statuses'));
    }

    public function store(StorePhoneNumberRequest $request)
    {
        if ($request->filled('bulk')) {
            return $this->storeBulk($request);
        }

        $data = $request->validated();
        $data['image'] = $this->resolveImage($request, null, 'numbers');

        $number = PhoneNumber::create($data);

        $this->logAudit(AuditAction::Create, $number, [], $number->toArray(), "Published number {$number->phone_number}");

        return redirect()->route('admin.numbers.index')
            ->with('success', 'Phone number published successfully.');
    }

    public function edit(PhoneNumber $number)
    {
        $statuses = PhoneNumberStatus::cases();

        return view('admin.numbers.edit', compact('number', 'statuses'));
    }

    public function update(UpdatePhoneNumberRequest $request, PhoneNumber $number)
    {
        $old = $number->toArray();
        $data = $request->validated();
        $data['image'] = $this->resolveImage($request, $number->image, 'numbers');
        $number->update($data);

        $this->logAudit(AuditAction::Update, $number, $old, $number->toArray(), "Updated number {$number->phone_number}");

        return redirect()->route('admin.numbers.index')
            ->with('success', 'Phone number updated successfully.');
    }

    public function destroy(PhoneNumber $number)
    {
        if ($number->numberPurchases()->exists()) {
            return back()->with('error', 'Cannot delete a number that has purchases. Disable it instead.');
        }

        $old = $number->toArray();
        $this->deleteStoredImage($number->image);
        $number->delete();

        $this->logAudit(AuditAction::Delete, $number, $old, [], "Deleted number {$number->phone_number}");

        return redirect()->route('admin.numbers.index')
            ->with('success', 'Phone number deleted successfully.');
    }

    protected function logAudit(AuditAction $action, PhoneNumber $number, array $old, array $new, string $description): void
    {
        AuditLog::create([
            'user_id' => request()->user()?->id,
            'auditable_type' => $number->getMorphClass(),
            'auditable_id' => $number->id,
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function storeBulk(StorePhoneNumberRequest $request)
    {
        $base = $request->safe()->except(['phone_number', 'bulk', 'image', 'image_file', 'remove_image']);

        $lines = collect(preg_split('/\r\n|\r|\n/', $request->bulk))
            ->map(fn ($line) => trim($line))
            ->filter();

        if ($lines->isEmpty()) {
            return back()->with('error', 'No numbers provided in the bulk field.')->withInput();
        }

        $created = 0;
        $duplicates = 0;

        foreach ($lines as $line) {
            $phoneNumber = ltrim(trim($line), '+');

            if (PhoneNumber::where('phone_number', $request->country_code . $phoneNumber)->exists()) {
                $duplicates++;
                continue;
            }

            PhoneNumber::create([
                'phone_number' => $request->country_code . $phoneNumber,
                'country' => $base['country'],
                'country_code' => $base['country_code'],
                'provider' => $base['provider'] ?? null,
                'provider_number_id' => $base['provider_number_id'] ?? null,
                'price' => $base['price'],
                'expires_at' => $base['expires_at'] ?? null,
                'status' => $base['status'] ?? PhoneNumberStatus::Available->value,
            ]);

            $created++;
        }

        $message = "Published {$created} number(s) successfully.";
        if ($duplicates > 0) {
            $message .= " Skipped {$duplicates} duplicate(s).";
        }

        return redirect()->route('admin.numbers.index')->with('success', $message);
    }

    public function purchases(Request $request)
    {
        $query = NumberPurchase::with('user', 'phoneNumber');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchases = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.numbers.purchases', compact('purchases'));
    }
}
