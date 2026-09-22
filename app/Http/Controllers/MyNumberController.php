<?php

namespace App\Http\Controllers;

use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyNumberController extends Controller
{
    public function index(Request $request)
    {
        $purchases = NumberPurchase::where('user_id', $request->user()->id)
            ->with('phoneNumber')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('my-numbers.index', compact('purchases'));
    }

    public function show(NumberPurchase $purchase)
    {
        $this->authorize('view', $purchase);

        $purchase->load('phoneNumber');

        $messageCount = NumberMessage::where('phone_number_id', $purchase->phone_number_id)->count();

        return view('my-numbers.show', compact('purchase', 'messageCount'));
    }

    public function update(Request $request, NumberPurchase $purchase)
    {
        $this->authorize('manage', $purchase);

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        $purchase->update(['label' => $validated['label'] ?: null]);

        return back()->with('success', 'Nickname updated.');
    }

    public function release(Request $request, NumberPurchase $purchase)
    {
        $this->authorize('manage', $purchase);

        if (! in_array($purchase->status->value, ['active', 'pending'], true)) {
            return back()->with('error', 'Only active numbers can be released.');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->update(['status' => NumberPurchaseStatus::Cancelled->value]);
            $this->freePhoneNumber($purchase->phone_number_id, $purchase->id);
        });

        return redirect()->route('my-numbers.index')
            ->with('success', 'Number released successfully.');
    }

    public function showReplace(NumberPurchase $purchase)
    {
        $this->authorize('manage', $purchase);

        if ($purchase->status->value !== 'active') {
            return redirect()->route('my-numbers.show', $purchase)
                ->with('error', 'Only active numbers can be replaced.');
        }

        $alternatives = PhoneNumber::where('status', PhoneNumberStatus::Available)
            ->where('id', '!=', $purchase->phone_number_id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('my-numbers.replace', compact('purchase', 'alternatives'));
    }

    public function replace(Request $request, NumberPurchase $purchase)
    {
        $this->authorize('manage', $purchase);

        if ($purchase->status->value !== 'active') {
            return back()->with('error', 'Only active numbers can be replaced.');
        }

        $validated = $request->validate([
            'phone_number_id' => ['required', 'integer'],
        ]);

        if ((int) $validated['phone_number_id'] === (int) $purchase->phone_number_id) {
            return back()->withErrors(['phone_number_id' => 'This number is already yours.']);
        }

        $newNumber = PhoneNumber::where('id', $validated['phone_number_id'])
            ->where('status', PhoneNumberStatus::Available)
            ->first();

        if (! $newNumber) {
            return back()->withErrors(['phone_number_id' => 'The selected number is no longer available.']);
        }

        DB::transaction(function () use ($request, $purchase, $newNumber) {
            $purchase->update(['status' => NumberPurchaseStatus::Cancelled->value]);
            $this->freePhoneNumber($purchase->phone_number_id, $purchase->id);

            NumberPurchase::create([
                'user_id' => $request->user()->id,
                'phone_number_id' => $newNumber->id,
                'label' => $purchase->label,
                'price' => $newNumber->price,
                'status' => NumberPurchaseStatus::Active->value,
                'purchased_at' => now(),
                'expires_at' => $newNumber->expires_at ?? now()->addDays((int) Setting::get('system.number_expiry_days', 30)),
            ]);

            $newNumber->update(['status' => PhoneNumberStatus::Active->value]);
        });

        return redirect()->route('my-numbers.index')
            ->with('success', 'Number replaced successfully. Any price difference will be settled with support on Telegram.');
    }

    protected function freePhoneNumber(int $phoneNumberId, int $exceptPurchaseId): void
    {
        $stillHeld = NumberPurchase::where('phone_number_id', $phoneNumberId)
            ->where('id', '!=', $exceptPurchaseId)
            ->whereIn('status', [NumberPurchaseStatus::Active->value, NumberPurchaseStatus::Pending->value])
            ->exists();

        if (! $stillHeld) {
            PhoneNumber::where('id', $phoneNumberId)
                ->update(['status' => PhoneNumberStatus::Available->value]);
        }
    }
}
