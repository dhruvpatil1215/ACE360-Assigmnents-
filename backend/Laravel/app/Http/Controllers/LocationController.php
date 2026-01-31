<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    protected $baseUrl = 'https://countriesnow.space/api/v0.1';

    /**
     * Get all countries.
     */
    public function countries()
    {
        $countries = Cache::remember('countries_list', 60 * 60 * 24, function () {
            $response = Http::get("{$this->baseUrl}/countries/positions");

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return collect($data)->map(function ($item) {
                    return ['country_name' => $item['name']];
                })->sortBy('country_name')->values()->toArray();
            }

            return [];
        });

        return response()->json($countries);
    }

    /**
     * Get states for a country.
     */
    public function states($country)
    {
        $cacheKey = 'states_' . md5($country);

        $states = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($country) {
            $response = Http::post("{$this->baseUrl}/countries/states", [
                'country' => $country
            ]);

            if ($response->successful()) {
                $data = $response->json('data.states') ?? [];
                return collect($data)->map(function ($item) {
                    return ['state_name' => $item['name']];
                })->sortBy('state_name')->values()->toArray();
            }

            return [];
        });

        return response()->json($states);
    }

    /**
     * Get cities for a state.
     */
    public function cities($state)
    {
        $cacheKey = 'cities_' . md5($state);

        $cities = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($state) {
            // CountriesNow requires country for cities, so we'll use a different approach
            // We'll fetch cities by state from a different endpoint
            $response = Http::post("{$this->baseUrl}/countries/state/cities", [
                'country' => request('country', ''),
                'state' => $state
            ]);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return collect($data)->map(function ($city) {
                    return ['city_name' => $city];
                })->sortBy('city_name')->values()->toArray();
            }

            return [];
        });

        return response()->json($cities);
    }
}
