<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketService::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(20);

        $categories = MarketService::distinct()->pluck('category')->filter()->values();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = 'active';

        MarketService::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully!');
    }

    public function edit(MarketService $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, MarketService $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(MarketService $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }
}
