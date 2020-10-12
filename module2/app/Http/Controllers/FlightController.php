<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    public function searching(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => [
                'required',
                function ($attribute, $value, $fail) {
                    $iata = ['KZN', 'SVO', 'LED', 'AER'];
                    if (!in_array($value, $iata)) {
                        $fail($attribute . ' is not IATA code');
                    }
                }
            ],
            'to' => [
                'required',
                function ($attribute, $value, $fail) {
                    $iata = ['KZN', 'SVO', 'LED', 'AER'];
                    if (!in_array($value, $iata)) {
                        $fail($attribute . ' is not IATA code');
                    }
                }
            ],
            'passengers' => 'required|min:1|max:8|numeric',
            'date1' => 'required|date_format:Y-m-d',
            'date2' => 'date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ]);
        }

        $fromId = Airport::query()->where('iata', $request->get('from'))->get('id');
        $toId = Airport::query()->where('iata', $request->get('to'))->get('id');

        $flightsTo = Flight::where([['from_id', $fromId], ['to_id', $toId]]);

        if

        return response()->json([
            'data' => [
                'flights_to' => []
            ]
        ]);
    }
}
