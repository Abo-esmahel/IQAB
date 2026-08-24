<?php

namespace App\Http\Controllers;

use App\Actions\PurchaseNumberAction;
use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Http\Requests\PurchaseNumberRequest;
use App\Models\PhoneNumber;
use App\Models\NumberPurchase;
use App\Notifications\NumberPurchasedNotification;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    public function index(Request $request)
    {
        $query = PhoneNumber::query()->where('status', PhoneNumberStatus::Available);

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $numbers = $query->orderByDesc('created_at')->paginate(12);

        $countries = PhoneNumber::distinct()->pluck('country')->filter()->values();

        return view('numbers.index', compact('numbers', 'countries'));
    }

    public function show(PhoneNumber $number)
    {
        return view('numbers.show', ['number' => $number]);
    }

    public function purchase(PurchaseNumberRequest $request, PurchaseNumberAction $action)
    {
        $number = PhoneNumber::findOrFail($request->phone_number_id);

        if ($number->status !== PhoneNumberStatus::Available) {
            return back()->with('error', 'This number is no longer available.');
        }

        try {
            $purchase = $action->execute($request->user(), $number);

            if ($purchase->status === NumberPurchaseStatus::Cancelled || $purchase->status === NumberPurchaseStatus::Pending) {
                return back()->with('error', 'The number could not be provisioned by the provider. Your payment was refunded. Please try again.');
            }

            $purchase->user->notify(new NumberPurchasedNotification($purchase));

            return redirect()->route('my-numbers.show', $purchase)
                ->with('success', 'Number purchased successfully!');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to complete the purchase. Please try again.');
        }
    }
}
