<?php

namespace App\Http\Controllers;

use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use Illuminate\Http\Request;

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
}
