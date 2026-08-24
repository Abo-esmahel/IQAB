<?php

namespace App\Http\Controllers;

use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activeNumbers = NumberPurchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $totalNumbers = NumberPurchase::where('user_id', $user->id)->count();

        $totalMessages = NumberMessage::where('user_id', $user->id)->count();

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
            'activeNumbers', 'totalNumbers', 'totalMessages',
            'recentPurchases', 'recentMessages'
        ));
    }
}
