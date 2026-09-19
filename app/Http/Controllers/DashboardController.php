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

        $dailyMessages = $this->dailyMessageCounts($user->id);
        $weekDelta = $this->weekDelta($user->id);

        return view('dashboard.index', compact(
            'activeNumbers', 'totalNumbers', 'totalMessages',
            'recentPurchases', 'recentMessages', 'dailyMessages', 'weekDelta'
        ));
    }

    public function live(Request $request)
    {
        $user = $request->user();

        $activeNumbers = NumberPurchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $totalNumbers = NumberPurchase::where('user_id', $user->id)->count();
        $totalMessages = NumberMessage::where('user_id', $user->id)->count();
        $totalPurchases = NumberPurchase::where('user_id', $user->id)->count();

        $messages = NumberMessage::with('phoneNumber')
            ->where('user_id', $user->id)
            ->orderByDesc('received_at')
            ->limit(5)
            ->get()
            ->map(fn ($m) => [
                'sender' => $m->sender ?? 'Unknown',
                'message' => $m->message,
                'time' => $m->received_at?->diffForHumans(),
                'initial' => strtoupper(substr($m->sender ?? '?', 0, 1)),
                'number' => $m->phoneNumber?->phone_number ?? '',
            ]);

        $purchases = NumberPurchase::with('phoneNumber')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'number' => $p->phoneNumber?->phone_number ?? 'N/A',
                'country' => $p->phoneNumber?->country ?? 'N/A',
                'status' => $p->status->value,
                'status_label' => $p->status->label(),
                'date' => $p->created_at->format('M d, Y'),
                'url' => route('my-numbers.show', $p),
            ]);

        return response()->json([
            'activeNumbers' => $activeNumbers,
            'totalNumbers' => $totalNumbers,
            'totalMessages' => $totalMessages,
            'totalPurchases' => $totalPurchases,
            'messages' => $messages,
            'purchases' => $purchases,
            'daily' => $this->dailyMessageCounts($user->id),
            'delta' => $this->weekDelta($user->id),
            'ts' => now()->format('H:i:s'),
        ]);
    }

    private function dailyMessageCounts(int $userId): array
    {
        $daily = NumberMessage::where('user_id', $userId)
            ->where('received_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(received_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $out = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $out[] = $daily[$day] ?? 0;
        }

        return $out;
    }

    private function weekDelta(int $userId): int
    {
        $current = NumberMessage::where('user_id', $userId)
            ->where('received_at', '>=', now()->subDays(6)->startOfDay())
            ->count();

        $previous = NumberMessage::where('user_id', $userId)
            ->whereBetween('received_at', [now()->subDays(13)->startOfDay(), now()->subDays(6)->startOfDay()])
            ->count();

        if ($previous === 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100);
    }
}