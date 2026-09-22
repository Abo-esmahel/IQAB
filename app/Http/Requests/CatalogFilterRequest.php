<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tab' => ['sometimes', 'nullable', 'string', 'in:all,numbers,services,offers'],
            'search' => ['sometimes', 'nullable', 'string', 'max:100'],
            'country' => ['sometimes', 'nullable', 'string', 'max:100'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'min_price' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:1000000'],
            'max_price' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:1000000'],
            'sort' => ['sometimes', 'nullable', 'string', 'in:newest,price_asc,price_desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'min_price.numeric' => 'Min price must be a number.',
            'max_price.numeric' => 'Max price must be a number.',
            'search.max' => 'Search must not exceed 100 characters.',
        ];
    }

    /**
     * Normalized filters with defaults + inverted price range auto-fixed.
     */
    public function filters(): array
    {
        $data = array_merge(
            ['tab' => 'all', 'sort' => 'newest'],
            array_filter($this->validated(), fn ($v) => $v !== null && $v !== '')
        );

        if (isset($data['min_price'], $data['max_price']) && $data['min_price'] > $data['max_price']) {
            [$data['min_price'], $data['max_price']] = [$data['max_price'], $data['min_price']];
        }

        return $data;
    }

    public function like(string $value): string
    {
        return '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value) . '%';
    }
}
