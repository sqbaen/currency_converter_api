<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ExchangeRate;
use Illuminate\Support\Carbon;

class DatabaseExchangeRateRepository implements ExchangeRateRepository{

    public function forDate(string $currencyFrom, string $currencyTo, Carbon $date): ?float
    {
        $exchangeRate = ExchangeRate::where('date', $date->toDateString())
            ->where('from', $currencyFrom)
            ->where('to', $currencyTo)
            ->first();

        return  $exchangeRate == null
            ?   null
            :   $exchangeRate->rate;
    }
}
