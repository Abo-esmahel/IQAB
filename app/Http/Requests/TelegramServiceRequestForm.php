<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramServiceRequestForm extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telegram_service_id' => ['required', 'exists:telegram_services,id'],
            'target_identifier' => ['required', 'string', 'max:255'],
        ];
    }
}
