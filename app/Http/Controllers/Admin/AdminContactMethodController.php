<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Models\ContactMethod;
use Illuminate\Http\Request;

class AdminContactMethodController extends Controller
{
    use HandlesImageUploads;

    public const TYPES = [
        'whatsapp', 'telegram', 'x', 'instagram', 'facebook', 'messenger',
        'discord', 'tiktok', 'youtube', 'snapchat', 'signal', 'phone', 'email',
        'twitter', 'viber', 'line', 'reddit', 'twitch', 'pinterest', 'other',
    ];
    public function index()
    {
        $contactMethods = ContactMethod::ordered()->get();

        return view('admin.contact-methods.index', compact('contactMethods'));
    }

    public function create()
    {
        $types = self::TYPES;

        return view('admin.contact-methods.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'in:' . implode(',', self::TYPES)],
            'value' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['image'] = $this->resolveImage($request, null, 'contact');

        ContactMethod::create($validated);

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method created successfully.');
    }

    public function edit(ContactMethod $contactMethod)
    {
        $types = self::TYPES;

        return view('admin.contact-methods.edit', compact('contactMethod', 'types'));
    }

    public function update(Request $request, ContactMethod $contactMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'in:' . implode(',', self::TYPES)],
            'value' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $contactMethod->is_active);
        $validated['sort_order'] = $validated['sort_order'] ?? $contactMethod->sort_order;
        $validated['image'] = $this->resolveImage($request, $contactMethod->image, 'contact');

        $contactMethod->update($validated);

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method updated successfully.');
    }

    public function destroy(ContactMethod $contactMethod)
    {
        $this->deleteStoredImage($contactMethod->image);
        $contactMethod->delete();

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method deleted successfully.');
    }
}
