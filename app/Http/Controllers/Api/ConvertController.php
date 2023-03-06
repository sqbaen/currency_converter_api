<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;
use App\Repositories\DatabaseExchangeRateRepository;
use App\Services\ExchangerService;
use App\Exceptions\ExchangeRateNotFoundException;

class ConvertController extends ApiController
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * Converts between two currencies
     * @param Request $request
     * @return JsonResponse
     */
    public function convert(Request $request): JsonResponse
    {
        $paramFrom = $request->input('from') ?? null;
        $paramTo = $request->input('to') ?? null;
        $paramAmount = $request->input('amount') ?? null;
        $paramDate = $request->input('date') ?? null;

        // Validation
        $supportedFrom = [
            Config::get('constants.currencies_codes.sgd')
        ];
        $supportedTo = [
            Config::get('constants.currencies_codes.pln')
        ];
        $errorMessages = [];
        $validatedFrom = null;
        $validatedTo = null;
        $validatedAmount = null;
        $validatedDate = null;

        if($paramFrom === null){
            $errorMessages[] = 'No \'from\' param.';
        }
        elseif(!in_array($paramFrom, $supportedFrom)){
            $errorMessages[] = 'Invalid \'from\' param. Only supported: '.implode(', ', $supportedFrom);
        }
        else {
            $validatedFrom = $paramFrom;
        }

        if($paramTo === null){
            $errorMessages[] = 'No \'to\' param.';
        }
        elseif(!in_array($paramTo, $supportedTo)){
            $errorMessages[] = 'Invalid \'to\' param. Only supported: '.implode(', ', $supportedTo);
        }
        else {
            $validatedTo = $paramTo;
        }

        if($paramAmount === null){
            $errorMessages[] = 'No \'amount\' param.';
        }
        elseif(!is_numeric($paramAmount)){
            $errorMessages[] = 'Invalid \'amount\' param.';
        }
        else {
            $validatedAmount = floatval($paramAmount);
        }

        // Date is optional
        if($paramDate === null){
            $validatedDate = Carbon::now();
        }
        else {
            try {
                $validatedDate = Carbon::createFromFormat(self::DATE_FORMAT, $paramDate);
            } catch (InvalidFormatException) {
                $errorMessages[] = 'Invalid \'date\' param.';
            }
        }


        // Validation passed?
        if(count($errorMessages) > 0){
            return $this->jsonResponseError($errorMessages, 400);
        } else {
            $exchangeService = new ExchangerService(
                new DatabaseExchangeRateRepository()
            );

            try {
                list('result' => $result, 'rate' => $rate) = $exchangeService->convert(
                    $validatedFrom, $validatedTo, $validatedDate, $validatedAmount
                );

            } catch (ExchangeRateNotFoundException) {
                return $this->jsonResponseError(
                    'No rates from ' . $validatedFrom . ' to ' . $validatedTo . ' for date: ' . $validatedDate->toDateString(),
                    400
                );
            }

            // Everything is OK - just return response
            $responseData = [
                'date' =>  $validatedDate->toDateString(),
                'rate' => $rate,
                'result' => $result,

                // Returns also query params
                'query' => $this->responseQueryData(
                    $validatedFrom,
                    $validatedTo,
                    $validatedAmount,
                    $paramDate === null
                        ?   null
                        :   $validatedDate
                )
            ];

            return $this->jsonResponseSuccess($responseData);
        }
    }

    private function responseQueryData(string $from, string $to, float $amount, ?Carbon $date): array
    {
        $responseQueryData = [
            'from'  => $from,
            'to'  => $to,
            'amount'  => $amount
        ];

        if($date !== null)
        {
            $responseDataQuery['date'] = $date->toDateString();
        }

        return $responseQueryData;
    }
}
