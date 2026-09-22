<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\ServicePurchase;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $numbersRevenue = (float) NumberPurchase::sum('price');
        $servicesRevenue = (float) ServicePurchase::sum('price');
        $telegramRevenue = (float) TelegramServiceRequest::where('status', 'completed')->sum('price');

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'new_users_7d' => User::where('created_at', '>=', $now->copy()->subDays(7))->count(),
            'total_revenue' => $numbersRevenue + $servicesRevenue + $telegramRevenue,
            'numbers_revenue' => $numbersRevenue,
            'services_revenue' => $servicesRevenue,
            'telegram_revenue' => $telegramRevenue,
            'total_purchases' => NumberPurchase::count() + ServicePurchase::count(),
            'active_numbers' => NumberPurchase::where('status', 'active')->count(),
            'available_numbers' => PhoneNumber::where('status', 'available')->count(),
            'telegram_total' => TelegramServiceRequest::count(),
            'telegram_processing' => TelegramServiceRequest::whereIn('status', ['pending', 'processing'])->count(),
            'telegram_failed' => TelegramServiceRequest::where('status', 'failed')->count(),
        ];

        $registrations = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $now->copy()->subDays(29)->startOfDay())
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd');

        $chart = [];
        $maxChart = 1;
        for ($i = 29; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $count = (int) ($registrations[$day] ?? 0);
            $chart[] = ['day' => $day, 'label' => $now->copy()->subDays($i)->format('M d'), 'count' => $count];
            $maxChart = max($maxChart, $count);
        }

        $topCountries = NumberPurchase::join('phone_numbers', 'phone_numbers.id', '=', 'number_purchases.phone_number_id')
            ->selectRaw('phone_numbers.country as country, COUNT(*) as total, SUM(number_purchases.price) as revenue')
            ->groupBy('phone_numbers.country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentUsers = User::orderByDesc('created_at')->limit(8)->get();
        $recentPurchases = NumberPurchase::with('user', 'phoneNumber')->orderByDesc('created_at')->limit(8)->get();
        $recentTelegram = TelegramServiceRequest::with('user', 'telegramService')->orderByDesc('created_at')->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'chart', 'maxChart', 'topCountries', 'recentUsers', 'recentPurchases', 'recentTelegram'));
    }
}
