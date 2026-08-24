<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\MarketService;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::with('relatedService', 'relatedNumber');

        if ($request->filled('search')) {
            $search = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->search) . '%';
            $query->where('title', 'like', $search);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        $offers = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(20);

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $services = MarketService::active()->orderBy('name')->get();
        $numbers = PhoneNumber::where('status', 'available')->orderBy('phone_number')->get();

        return view('admin.offers.create', compact('services', 'numbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:50'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'offer_price' => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'type' => ['required', 'string', 'in:service,number,bundle,other'],
            'related_service_id' => ['nullable', 'integer', 'exists:market_services,id'],
            'related_number_id' => ['nullable', 'integer', 'exists:phone_numbers,id'],
            'cta_text' => ['nullable', 'string', 'max:50'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Offer::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['cta_text'] = $validated['cta_text'] ?? 'Get Offer';
        $validated['used_count'] = 0;

        Offer::create($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully!');
    }

    public function edit(Offer $offer)
    {
        $services = MarketService::active()->orderBy('name')->get();
        $numbers = PhoneNumber::where('status', 'available')->orderBy('phone_number')->get();

        return view('admin.offers.edit', compact('offer', 'services', 'numbers'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:50'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'offer_price' => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'type' => ['required', 'string', 'in:service,number,bundle,other'],
            'related_service_id' => ['nullable', 'integer', 'exists:market_services,id'],
            'related_number_id' => ['nullable', 'integer', 'exists:phone_numbers,id'],
            'cta_text' => ['nullable', 'string', 'max:50'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        if (isset($validated['title']) && $validated['title'] !== $offer->title) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Offer::where('slug', $slug)->where('id', '!=', $offer->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_active'] = $request->boolean('is_active', $offer->is_active);
        $validated['is_featured'] = $request->boolean('is_featured', $offer->is_featured);

        $offer->update($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully!');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted.');
    }
}
