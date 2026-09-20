<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TelegramServiceType;
use App\Http\Controllers\Controller;
use App\Models\TelegramService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTelegramServiceController extends Controller
{
    public function index(): View
    {
        $services = TelegramService::orderBy('created_at')->get();

        return view('admin.telegram-services.index', compact('services'));
    }

    public function create(): View
    {
        $types = TelegramServiceType::cases();

        return view('admin.telegram-services.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', array_column(TelegramServiceType::cases(), 'value'))],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        TelegramService::create($validated);

        return redirect()->route('admin.telegram-services.index')
            ->with('success', 'Telegram service created successfully.');
    }

    public function edit(TelegramService $telegramService): View
    {
        $types = TelegramServiceType::cases();

        return view('admin.telegram-services.edit', compact('telegramService', 'types'));
    }

    public function update(Request $request, TelegramService $telegramService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', array_column(TelegramServiceType::cases(), 'value'))],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $telegramService->update($validated);

        return redirect()->route('admin.telegram-services.index')
            ->with('success', 'Telegram service updated successfully.');
    }

    public function destroy(TelegramService $telegramService): RedirectResponse
    {
        if ($telegramService->telegramServiceRequests()->exists()) {
            return back()->with('error', 'Cannot delete a service that has requests. Disable it instead.');
        }

        $telegramService->delete();

        return redirect()->route('admin.telegram-services.index')
            ->with('success', 'Telegram service deleted successfully.');
    }
}
