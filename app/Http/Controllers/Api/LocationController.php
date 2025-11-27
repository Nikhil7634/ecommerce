<?php
// app/Http/Controllers/Api/LocationController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    protected $apiUrl = 'https://countriesnow.space/api/v0.1/countries';

    public function countries()
    {
        $response = Http::get("{$this->apiUrl}");
        $countries = collect($response->json('data'))->pluck('country')->sort()->values();

        return response()->json($countries);
    }

    public function states($country)
    {
        $response = Http::post("{$this->apiUrl}/states", [
            'country' => $country
        ]);

        if ($response->successful()) {
            $states = collect($response->json('data.states'))->pluck('name')->sort()->values();
            return response()->json($states);
        }

        return response()->json([]);
    }

    public function cities($country, $state)
    {
        $response = Http::post("{$this->apiUrl}/states/cities", [
            'country' => $country,
            'state' => $state
        ]);

        if ($response->successful()) {
            $cities = $response->json('data');
            return response()->json($cities);
        }

        return response()->json([]);
    }
}