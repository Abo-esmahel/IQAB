<?php

namespace App\Http\Requests\Admin;

use App\Enums\PhoneNumberStatus;
use Illuminate\Foundation\Http\FormRequest;

class StorePhoneNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'phone_number' => ['required_without:bulk', 'string', 'max:30', 'unique:phone_numbers,phone_number'],
            'bulk' => ['nullable', 'string'],
            'country' => ['required', 'string', 'max:100'],
            'country_code' => ['required', 'string', 'regex:/^\+\d{1,4}$/'],
            'provider' => ['nullable', 'string', 'max:100'],
            'provider_number_id' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'expires_at' => ['nullable', 'date'],
            'status' => ['nullable', 'in:' . implode(',', $this->statuses())],
        ];
    }

    public function messages(): array
    {
        return [
            'country_code.regex' => 'The country code must look like +1, +44, +966, etc.',
            'phone_number.unique' => 'This phone number is already published.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('expires_at') === '') {
            $this->merge(['expires_at' => null]);
        }
    }

    protected function statuses(): array
    {
        return array_map(fn ($case) => $case->value, PhoneNumberStatus::cases());
    }
}
