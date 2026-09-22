<?php

namespace App\Http\Controllers;

use App\Enums\PhoneNumberStatus;
use App\Http\Requests\CatalogFilterRequest;
use App\Models\PhoneNumber;
use App\Models\MarketService;
use App\Models\Offer;

class MarketplaceController extends Controller
{
    public function index(CatalogFilterRequest $request)
    {
        $f = $request->filters();
        $tab = $f['tab'];

        $numbers = PhoneNumber::where('status', PhoneNumberStatus::Available);
        $services = MarketService::active();
        $offers = Offer::active();

        if (! empty($f['search'])) {
            $search = $request->like($f['search']);
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

        if (! empty($f['country'])) {
            $numbers->where('country', $f['country']);
        }

        if (! empty($f['category'])) {
            $services->where('category', $f['category']);
        }

        if (isset($f['min_price'])) {
            $numbers->where('price', '>=', $f['min_price']);
            $services->where('price', '>=', $f['min_price']);
            $offers->where('offer_price', '>=', $f['min_price']);
        }

        if (isset($f['max_price'])) {
            $numbers->where('price', '<=', $f['max_price']);
            $services->where('price', '<=', $f['max_price']);
            $offers->where('offer_price', '<=', $f['max_price']);
        }

        $this->applySort($numbers, $f['sort'], 'price', 'created_at');
        if ($f['sort'] === 'newest') {
            $services->orderBy('sort_order')->orderByDesc('created_at');
        } else {
            $this->applySort($services, $f['sort'], 'price', 'created_at');
        }

        $numbers = $numbers->paginate(12)->withQueryString();
        $services = $services->paginate(12)->withQueryString();
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

    protected function applySort($query, string $sort, string $priceColumn, string $dateColumn): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy($priceColumn)->orderByDesc($dateColumn),
            'price_desc' => $query->orderByDesc($priceColumn)->orderByDesc($dateColumn),
            default => $query->orderByDesc($dateColumn),
        };
    }
}
