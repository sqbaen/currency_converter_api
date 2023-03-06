<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExchangeRate::truncate();

        // Create ExchangeRates - Today and 13 days before
        $exchangeRatesCount = 14;
        $date = Carbon::now();

        for ($i=0; $i < $exchangeRatesCount; $i++) {
            ExchangeRate::factory()
                ->fromSGDToPLN()
                ->create([
                    'date'  => $date->toDateString()
                ]);

            $date->subDay();
        }
    }
}
