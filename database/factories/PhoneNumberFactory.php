<?php

namespace Database\Factories;

use App\Enums\PhoneNumberStatus;
use App\Models\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneNumberFactory extends Factory
{
    protected $model = PhoneNumber::class;

    public function definition(): array
    {
        return [
            'phone_number' => $this->faker->phoneNumber,
            'provider' => $this->faker->word,
            'country' => $this->faker->country,
            'country_code' => '+' . $this->faker->numberBetween(1, 999),
            'provider_number_id' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'status' => PhoneNumberStatus::Available->value,
            'metadata' => [],
            'expires_at' => null,
        ];
    }
}