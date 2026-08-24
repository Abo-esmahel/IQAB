<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMethod;
use Illuminate\Http\Request;

class AdminContactMethodController extends Controller
{
    public function index()
    {
        $contactMethods = ContactMethod::ordered()->get();

        return view('admin.contact-methods.index', compact('contactMethods'));
    }

    public function create()
    {
        return view('admin.contact-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:whatsapp,telegram,email,phone,twitter,instagram,discord,facebook,other',
            'value' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        ContactMethod::create($validated);

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method created successfully.');
    }

    public function edit(ContactMethod $contactMethod)
    {
        return view('admin.contact-methods.edit', compact('contactMethod'));
    }

    public function update(Request $request, ContactMethod $contactMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:whatsapp,telegram,email,phone,twitter,instagram,discord,facebook,other',
            'value' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $contactMethod->update($validated);

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method updated successfully.');
    }

    public function destroy(ContactMethod $contactMethod)
    {
        $contactMethod->delete();

        return redirect()->route('admin.contact-methods.index')
            ->with('success', 'Contact method deleted successfully.');
    }
}
