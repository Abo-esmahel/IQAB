<?php

namespace App\Http\Controllers;

use App\Models\MarketService;
use App\Models\ServicePurchase;
use App\Models\WalletTransaction;
use App\Enums\TransactionType;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketService::where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $services = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(12);
        $categories = MarketService::where('status', 'active')->distinct()->pluck('category')->filter()->values();
        $featured = MarketService::where('status', 'active')->where('is_featured', true)->take(6)->get();

        return view('services.index', compact('services', 'categories', 'featured'));
    }

    public function show(MarketService $service)
    {
        if ($service->status !== 'active') {
            abort(404);
        }

        return view('services.show', compact('service'));
    }

    public function purchase(Request $request, MarketService $service)
    {
        if ($service->status !== 'active') {
            return back()->with('error', 'This service is not available.');
        }

        $validated = $request->validate([
            'input_data' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $price = (float) $service->price;

        try {
            $purchase = DB::transaction(function () use ($user, $service, $price, $validated) {
                $wallet = app(WalletService::class)->getOrCreateWallet($user);

                $balanceBefore = (float) $wallet->balance;

                if ($balanceBefore < $price) {
                    throw new \Exception('Insufficient balance.');
                }

                $balanceAfter = $balanceBefore - $price;

                $wallet->update([
                    'balance' => $balanceAfter,
                    'total_spent' => (float) $wallet->total_spent + $price,
                ]);

                $purchase = ServicePurchase::create([
                    'user_id' => $user->id,
                    'market_service_id' => $service->id,
                    'price' => $price,
                    'status' => 'completed',
                    'input_data' => $validated['input_data'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'result' => ['message' => 'Service purchased successfully. Processing will begin shortly.'],
                ]);

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => TransactionType::ServiceCharge->value,
                    'amount' => $price,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'reference' => "service_purchase_{$purchase->id}",
                    'description' => "Service: {$service->name}",
                    'status' => 'completed',
                ]);

                return $purchase;
            });

            return redirect()->route('services.result', $purchase)
                ->with('success', 'Service purchased successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function result(ServicePurchase $purchase)
    {
        $this->authorize('view', $purchase);

        $purchase->load('marketService');

        return view('services.result', compact('purchase'));
    }

    public function history(Request $request)
    {
        $purchases = ServicePurchase::where('user_id', $request->user()->id)
            ->with('marketService')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('services.history', compact('purchases'));
    }
}
