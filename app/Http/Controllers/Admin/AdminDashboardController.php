<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NumberPurchase;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_purchases' => NumberPurchase::count(),
            'active_numbers' => NumberPurchase::where('status', 'active')->count(),
        ];

        $recentUsers = User::orderByDesc('created_at')->limit(10)->get();
        $recentPurchases = NumberPurchase::with('user', 'phoneNumber')->orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentPurchases'));
    }
}
