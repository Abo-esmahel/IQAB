<?php

namespace App\Http\Controllers;

use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\WalletTransaction;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected WalletService $walletService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $balance = $this->walletService->getBalance($user);

        $activeNumbers = NumberPurchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $totalNumbers = NumberPurchase::where('user_id', $user->id)->count();

        $totalMessages = NumberMessage::where('user_id', $user->id)->count();

        $recentTransactions = WalletTransaction::where('wallet_id', $user->wallet?->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentPurchases = NumberPurchase::where('user_id', $user->id)
            ->with('phoneNumber')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentMessages = NumberMessage::where('user_id', $user->id)
            ->with('phoneNumber')
            ->orderByDesc('received_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'balance', 'activeNumbers', 'totalNumbers', 'totalMessages',
            'recentTransactions', 'recentPurchases', 'recentMessages'
        ));
    }
}
