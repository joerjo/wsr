<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function searching(Request $request)
    {
        $query = $request->get('query');

        $airports = Airport::where('city', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->orWhere('iata', 'LIKE', '%' . $query . '%')
            ->get();

        if (isset($airports)) {
            return response()->json([
                'data' => [
                    'items' => $airports->map(function ($airport) {
                        return [
                            'name' => $airport->name,
                            'iata' => $airport->iata
                        ];
                    })
                ]
            ]);
        }

        return response()->json(['data' => ['items' => []]]);
    }
}
