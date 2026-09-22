<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\MarketService;

class ServiceController extends Controller
{
    public function index(CatalogFilterRequest $request)
    {
        $f = $request->filters();
        $query = MarketService::active();

        if (! empty($f['category'])) {
            $query->where('category', $f['category']);
        }

        if (! empty($f['search'])) {
            $search = $request->like($f['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        match ($f['sort']) {
            'price_asc' => $query->orderBy('price')->orderBy('sort_order'),
            'price_desc' => $query->orderByDesc('price')->orderBy('sort_order'),
            default => $query->orderBy('sort_order')->orderByDesc('created_at'),
        };

        $services = $query->paginate(12)->withQueryString();
        $categories = MarketService::active()->distinct()->pluck('category')->filter()->values();
        $featured = MarketService::active()->featured()->take(6)->get();

        return view('services.index', compact('services', 'categories', 'featured'));
    }

    public function show(MarketService $service)
    {
        if (! $service->is_active) {
            abort(404);
        }

        return view('services.show', compact('service'));
    }
}
