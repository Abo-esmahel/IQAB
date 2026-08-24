<?php

namespace App\Http\Controllers;

use App\Enums\PhoneNumberStatus;
use App\Models\PhoneNumber;
use App\Models\MarketService;
use App\Models\Offer;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');

        $numbers = PhoneNumber::where('status', PhoneNumberStatus::Available);
        $services = MarketService::active();
        $offers = Offer::active();

        if ($request->filled('search')) {
            $search = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->search) . '%';
            $numbers->where(function ($q) use ($search) {
                $q->where('phone_number', 'like', $search)
                  ->orWhere('country', 'like', $search);
            });
            $services->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
            $offers->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        if ($request->filled('country')) {
            $numbers->where('country', $request->country);
        }

        if ($request->filled('category')) {
            $services->where('category', $request->category);
        }

        if ($request->filled('min_price')) {
            $numbers->where('price', '>=', $request->min_price);
            $services->where('price', '>=', $request->min_price);
            $offers->where('offer_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $numbers->where('price', '<=', $request->max_price);
            $services->where('price', '<=', $request->max_price);
            $offers->where('offer_price', '<=', $request->max_price);
        }

        $numbers = $numbers->orderByDesc('created_at')->paginate(12)->withQueryString();
        $services = $services->orderBy('sort_order')->orderByDesc('created_at')->paginate(12)->withQueryString();
        $offers = $offers->featured()->take(6)->get();

        $countries = PhoneNumber::where('status', PhoneNumberStatus::Available)->distinct()->pluck('country')->filter()->values();
        $categories = MarketService::active()->distinct()->pluck('category')->filter()->values();

        $allCount = PhoneNumber::where('status', PhoneNumberStatus::Available)->count() + MarketService::active()->count();
        $numbersCount = PhoneNumber::where('status', PhoneNumberStatus::Available)->count();
        $servicesCount = MarketService::active()->count();
        $offersCount = Offer::active()->count();

        return view('marketplace.index', compact(
            'tab', 'numbers', 'services', 'offers', 'countries', 'categories',
            'allCount', 'numbersCount', 'servicesCount', 'offersCount'
        ));
    }
}
