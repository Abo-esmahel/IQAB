<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdjustBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'not_in:0'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
