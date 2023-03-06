<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Carbon;

interface ExchangeRateRepository {

    public function forDate(string $currencyFrom, string $currencyTo, Carbon $date): ?float;
}
