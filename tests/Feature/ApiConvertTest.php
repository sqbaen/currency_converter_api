<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;

class ApiConvertTest extends TestCase
{
    public function testReturnsStatus400WithoutAnyParam(): void
    {
        $response = $this->json('GET', 'api/convert', []);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithoutParamFrom(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithoutParamTo(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'amount' => 11
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithoutParamAmount(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln')
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithInvalidParamCurrencyFrom(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => 'EUR',
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithInvalidParamCurrencyTo(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => 'USD',
            'amount' => 11
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus200WithoutParamDate(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11
        ]);

        $response->assertStatus(200);
    }

    public function testReturnsStatus200WithParamDateToday(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11,
            'date'  => Carbon::now()->toDateString()
        ]);

        $response->assertStatus(200);
    }

    public function testReturnsStatus400WithParamDateTomorrow(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11,
            'date'  => Carbon::tomorrow()->toDateString()
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus400WithParamDateInInvalidFormat(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln'),
            'amount' => 11,
            'date'  => Carbon::tomorrow()->toDateTimeString()
        ]);

        $response->assertStatus(400);
    }

    public function testReturnsStatus405WithMethodPost(): void
    {
        $response = $this->json('POST', 'api/convert', []);

        $response->assertStatus(405);
    }

    public function testReturnsStatus405WithMethodPut(): void
    {
        $response = $this->json('PUT', 'api/convert', []);

        $response->assertStatus(405);
    }

    public function testReturnsStatus405WithMethodPatch(): void
    {
        $response = $this->json('PATCH', 'api/convert', []);

        $response->assertStatus(405);
    }

    public function testReturnsStatus405WithMethodDelete(): void
    {
        $response = $this->json('DELETE', 'api/convert', []);

        $response->assertStatus(405);
    }

    public function testReturnsStatus405WithMethodOptions(): void
    {
        $response = $this->json('OPTIONS', 'api/convert', []);

        $response->assertStatus(405);
    }

    public function testErrorResponseJSONStructureHasAllKeys(): void
    {
        $response = $this->json('GET', 'api/convert', [
            'from'  => Config::get('constants.currencies_codes.sgd'),
            'to'  => Config::get('constants.currencies_codes.pln')
        ]);

        $response = $response->json();

        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('error', $response);
        $this->assertArrayHasKey('info', $response['error']);
        $this->assertIsArray($response['error']['info']);
        $this->assertGreaterThanOrEqual(1, count($response['error']['info']));
    }

    public function testSuccessResponseJSONStructureHasAllKeys(): void
    {
        $from = Config::get('constants.currencies_codes.sgd');
        $to = Config::get('constants.currencies_codes.pln');
        $amount = 11;
        $date = Carbon::now()->toDateString();

        $response = $this->json('GET', 'api/convert', [
            'from'  => $from,
            'to'  => $to,
            'amount' => $amount,
            'date'  => $date
        ]);

        $response = $response->json();

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);

        $this->assertArrayHasKey('date', $response);
        $this->assertEquals($date, $response['date']);

        $this->assertArrayHasKey('rate', $response);
        $this->assertIsFloat($response['rate']);

        $this->assertArrayHasKey('result', $response);
        $this->assertIsFloat($response['result']);

        $this->assertArrayHasKey('query', $response);
        $this->assertIsArray($response['query']);

        $this->assertArrayHasKey('from', $response['query']);
        $this->assertIsString($response['query']['from']);
        $this->assertEquals($from, $response['query']['from']);

        $this->assertArrayHasKey('to', $response['query']);
        $this->assertIsString($response['query']['to']);
        $this->assertEquals($to, $response['query']['to']);

        $this->assertArrayHasKey('amount', $response['query']);
        $this->assertIsNumeric($response['query']['amount']);
        $this->assertEquals($amount, $response['query']['amount']);
    }
}
