<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TelegramServiceRequestForm extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telegram_service_id' => [
                'required',
                'integer',
                Rule::exists('telegram_services', 'id')->where('is_active', true),
            ],
            'target_identifier' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/\A[@+]?[A-Za-z0-9_.]+\z/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_service_id.exists' => 'The selected service is not available.',
            'target_identifier.regex' => 'Enter a valid username (e.g. @user), phone (+966...), or numeric ID.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('target_identifier'))) {
            $this->merge(['target_identifier' => trim($this->input('target_identifier'))]);
        }
    }
}
