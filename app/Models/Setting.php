<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public const CACHE_KEY = 'iqab.settings.all';

    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_URL = 'url';
    public const TYPE_SECRET = 'secret';

    public static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            self::TYPE_INTEGER => is_numeric($value) ? (int) $value : 0,
            self::TYPE_BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default => $value,
        };
    }

    public function typedValue(): mixed
    {
        return self::castValue($this->value, $this->type);
    }

    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::all()->keyBy('key')->toArray();
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::allCached();

        if (! isset($all[$key])) {
            return $default;
        }

        $row = $all[$key];

        return self::castValue($row['value'], $row['type']);
    }

    public static function set(
        string $key,
        mixed $value,
        ?string $type = null,
        ?string $group = null,
        ?string $description = null
    ): self {
        $existing = self::where('key', $key)->first();

        if ($existing) {
            $existing->update([
                'value' => (string) $value,
                'type' => $type ?? $existing->type,
                'group' => $group ?? $existing->group,
                'description' => $description ?? $existing->description,
            ]);

            $model = $existing;
        } else {
            $model = self::create([
                'key' => $key,
                'value' => (string) $value,
                'type' => $type ?? self::TYPE_STRING,
                'group' => $group ?? 'general',
                'description' => $description,
            ]);
        }

        self::forgetCache();

        return $model;
    }

    public static function setMany(array $items): void
    {
        foreach ($items as $key => $data) {
            $data = is_array($data) ? $data : ['value' => $data];

            self::set(
                $key,
                $data['value'] ?? '',
                $data['type'] ?? self::TYPE_STRING,
                $data['group'] ?? 'general',
                $data['description'] ?? null
            );
        }
    }
}
