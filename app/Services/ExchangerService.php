<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use App\Repositories\ExchangeRateRepository;
use App\Exceptions\ExchangeRateNotFoundException;

class ExchangerService
{
    public function __construct(private ExchangeRateRepository $exchangeRateRepository)
    {
    }

    public function convert(string $currencyFrom, string $currencyTo, Carbon $date, float $amount): array
    {
        $rate = $this->exchangeRateRepository->forDate($currencyFrom, $currencyTo, $date);

        if($rate === null){
            throw new ExchangeRateNotFoundException();
        }
        else {
            $result = $this->calculateConversion($rate, $amount);

            return array(
                'rate'      => $rate,
                'result'    => $result
            );
        }
    }

    public function calculateConversion(float $rate, float $amount): float
    {
        $result = $rate * $amount;

        // Round it to exact precision
        $result = round($result, Config::get('constants.currencies_round_precision'));

        return $result;
    }
}
