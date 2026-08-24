<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    protected array $schema = [
        'phone' => [
            'label' => 'Phone Provider',
            'icon' => 'device-phone-mobile',
            'settings' => [
                'phoneprovider.base_url' => [
                    'label' => 'Provider API URL',
                    'type' => 'url',
                    'placeholder' => 'https://provider.example.com',
                    'description' => 'Base URL of the phone numbers provider API.',
                ],
                'phoneprovider.token' => [
                    'label' => 'Provider API Token',
                    'type' => 'secret',
                    'placeholder' => 'token',
                    'description' => 'Bearer token for the provider API.',
                ],
                'phoneprovider.webhook_secret' => [
                    'label' => 'Webhook Secret',
                    'type' => 'secret',
                    'placeholder' => 'secret',
                    'description' => 'Shared secret to verify provider webhooks (HMAC).',
                ],
                'phoneprovider.timeout' => [
                    'label' => 'Timeout (seconds)',
                    'type' => 'integer',
                    'placeholder' => '30',
                    'description' => 'HTTP timeout for provider requests.',
                ],
            ],
        ],
        'telegram' => [
            'label' => 'Telegram',
            'icon' => 'paper-airplane',
            'settings' => [
                'telegram.api_url' => [
                    'label' => 'Service API URL',
                    'type' => 'url',
                    'placeholder' => 'https://telegram-service.example.com',
                    'description' => 'URL for the Telegram lookup/report service.',
                ],
                'telegram.api_token' => [
                    'label' => 'Service API Token',
                    'type' => 'secret',
                    'placeholder' => 'token',
                    'description' => 'Bearer token for the Telegram service.',
                ],
                'telegram.bot_token' => [
                    'label' => 'Bot Token',
                    'type' => 'secret',
                    'placeholder' => '123456:ABC-defGHI',
                    'description' => 'Real Bot API token used for getMe, webhook and messages.',
                ],
                'telegram.webhook_secret' => [
                    'label' => 'Webhook Secret',
                    'type' => 'secret',
                    'placeholder' => 'secret',
                    'description' => 'secret_token expected on incoming bot webhooks.',
                ],
                'telegram.timeout' => [
                    'label' => 'Timeout (seconds)',
                    'type' => 'integer',
                    'placeholder' => '30',
                    'description' => 'HTTP timeout for Telegram requests.',
                ],
            ],
        ],
        'system' => [
            'label' => 'System & Initial Data',
            'icon' => 'cog',
            'settings' => [
                'system.currency' => [
                    'label' => 'Default Currency',
                    'type' => 'string',
                    'placeholder' => 'SAR',
                    'description' => 'Currency code used across the system.',
                ],
                'system.number_expiry_days' => [
                    'label' => 'Default Number Expiry (days)',
                    'type' => 'integer',
                    'placeholder' => '30',
                    'description' => 'Default lifetime applied when publishing a number without expiry.',
                ],
                'system.expiry_notify_hours' => [
                    'label' => 'Expiry Notify Before (hours)',
                    'type' => 'string',
                    'placeholder' => '24,6,1',
                    'description' => 'Comma separated hours before expiry to notify users.',
                ],
                'system.maintenance_mode' => [
                    'label' => 'Maintenance Mode',
                    'type' => 'boolean',
                    'description' => 'Temporarily disable customer-facing operations.',
                ],
            ],
        ],
    ];

    public function index(): View
    {
        $groups = [];

        foreach ($this->schema as $groupKey => $group) {
            $settings = [];

            foreach ($group['settings'] as $key => $meta) {
                $setting = Setting::where('key', $key)->first();
                $value = $setting?->typedValue();

                if ($meta['type'] === 'boolean') {
                    $value = (bool) ($value ?? false);
                } elseif ($meta['type'] === 'integer') {
                    $value = $value ?? (int) ($meta['placeholder'] ?? 0);
                } else {
                    $value = $value ?? '';
                }

                $settings[$key] = array_merge($meta, [
                    'value' => $value,
                    'group' => $groupKey,
                ]);
            }

            $groups[$groupKey] = array_merge($group, ['settings' => $settings]);
        }

        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        $attributes = [];

        foreach ($this->schema as $group) {
            foreach ($group['settings'] as $key => $meta) {
                $attributes['settings.' . $key] = $meta['label'];

                if ($meta['type'] === 'url') {
                    $rules['settings.' . $key] = ['nullable', 'string', 'url'];
                } elseif ($meta['type'] === 'integer') {
                    $rules['settings.' . $key] = ['nullable', 'integer', 'min:0'];
                } elseif ($meta['type'] === 'boolean') {
                    $rules['settings.' . $key] = ['nullable', 'boolean'];
                } else {
                    $rules['settings.' . $key] = ['nullable', 'string'];
                }
            }
        }

        $request->validate($rules, [], $attributes);

        $input = $request->input('settings', []);

        $payload = [];
        foreach ($this->schema as $groupKey => $group) {
            foreach ($group['settings'] as $key => $meta) {
                if ($meta['type'] === 'boolean') {
                    $value = filter_var(Arr::get($input, $key), FILTER_VALIDATE_BOOLEAN);
                } else {
                    $value = Arr::get($input, $key);
                }

                if ($meta['type'] === 'secret' && ($value === null || $value === '')) {
                    continue;
                }

                $payload[$key] = [
                    'value' => $value === null ? '' : $value,
                    'type' => $meta['type'],
                    'group' => $groupKey,
                    'description' => $meta['description'] ?? null,
                ];
            }
        }

        Setting::setMany($payload);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
