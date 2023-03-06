<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExchangeRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function fromSGDToPLN(): Factory
    {
        return $this->state(function (array $attributes){
            return [
                'from'  => Config::get('constants.currencies_codes.sgd'),
                'to'    => Config::get('constants.currencies_codes.pln'),
                'rate'  => fake()->randomFloat(6, 3.15, 3.65)
            ];
        });
    }
}
