<?php

namespace App\Http\Controllers;

use App\Models\MarketService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketService::active();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        $services = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(12);
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
