<?php

namespace App\Http\Controllers;

use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(NumberPurchase $purchase, Request $request)
    {
        $this->authorize('viewInbox', $purchase);

        $purchase->load('phoneNumber');

        $messages = NumberMessage::where('phone_number_id', $purchase->phone_number_id)
            ->orderByDesc('received_at')
            ->paginate(20);

        return view('inbox.index', compact('purchase', 'messages'));
    }

    public function show(NumberPurchase $purchase, NumberMessage $message, Request $request)
    {
        $this->authorize('viewInbox', $purchase);

        abort_unless($message->phone_number_id === $purchase->phone_number_id, 404);

        $message->update(['is_read' => true]);

        return view('inbox.show', ['purchase' => $purchase, 'message' => $message]);
    }
}
