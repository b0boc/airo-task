<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Quotation;
use App\Models\QuotationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class QuotationsController extends Controller
{
    const BASE_FARE = 3;
    const BASE_CURRENCY = 'USD';
    const CURRENCY_RATES = [
        'USD' => 1,
        'EUR' => 0.92,
        'GBP' => 0.78
    ];

    const MIN_AGE_LOAD = 0.6;


    /**
     * @param Request $request
     * 
     * @return string
     * 
     */
    public function getQuote(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'age' => 'required|regex:/^\d+(,\d+)*$/',
                'currency_id' => 'required|size:3',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $quotationRequest = new QuotationRequest($request->all());

            $response = $this->getQuoteModel($quotationRequest);

            return response()->json($response, Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param QuotationRequest $quotationRequest
     * 
     * @return Quotation
     * @throws ValidationException
     * @throws Exception
     */
    private function getQuoteModel(QuotationRequest $quotationRequest)
    {
        $quote = new Quotation();
        $quote->currency_id = $quotationRequest->currency_id;

        $quote->total = 0;

        // Get a total / day 
        $ages = array_map('intval', explode(',', $quotationRequest->age));
        foreach ($ages as $age) {
            if ($age > 70 || $age < 18) {
                throw ValidationException::withMessages(['Age is not valid 18-70']);
            }
            $quote->total += self::BASE_FARE * $this->getAgeLoad($age);
        }

        // Multiply total/day with quotation interval
        $quote->total = $quote->total * $this->getInterval($quotationRequest);

        // Currency tranformation
        if ($quote->currency_id !== self::BASE_CURRENCY) {
            $quote->total = $this->convertCurrency($quote->total, $quote->currency_id);
        }

        // Format totals
        $quote->total = number_format($quote->total, 2, '.', ',');

        // Save quotation
        $quote->save();

        // Numeric id - MongoDB
        $quote->quotation_id = Quotation::count();

        // Create and return the quote
        return $quote;
    }

    /**
     * @param int $age
     * 
     * @return float
     */
    private function getAgeLoad($age)
    {
        // The age load decreases with 0.1 for each age group, from 10 to 10. Only exception is 0.6. 
        // From 18-21 should be 0.5, to compensate for this we allow min 0.6
        return max(self::MIN_AGE_LOAD, 1 - floor((70 - $age) / 10) * 0.1);
    }

    /**
     * @param QuotationRequest $quotationRequest
     * 
     * @return int
     */
    private function getInterval(QuotationRequest $quotationRequest)
    {
        return Carbon::parse($quotationRequest->start_date)
            ->diffInDays(Carbon::parse($quotationRequest->end_date)) + 1;
    }

    /**
     * @param float $amount
     * @param string $currency
     * 
     * @return float
     * @throws Exception
     */
    private function convertCurrency($amount, $currency)
    {
        if (!isset(self::CURRENCY_RATES[$currency])) {
            throw new Exception("Invalid currency: $currency");
        }
        return $amount * self::CURRENCY_RATES[$currency];
    }
}
