<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use App\Services\ExchangerService;
use App\Repositories\DatabaseExchangeRateRepository;

class ExchangerServiceTest extends TestCase
{
    public function testConversionCalculationIsCorrect(): void
    {
        $rate = 2.67;
        $amount = 34.5;

        $exchangerService = new ExchangerService(new DatabaseExchangeRateRepository());
        $result = $exchangerService->calculateConversion($rate, $amount);

        $this->assertEquals(
            round($rate * $amount, Config::get('constants.currencies_round_precision')),
            $result
        );
    }
}
