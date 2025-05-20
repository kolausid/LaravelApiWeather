<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class WeatherController extends Controller
{
    public function getWeather($city) {
        $apiKey = config('app.openweather_api_key');
        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ru'
        ]);

        if ($response->successful()) {
            return response()->json([
                'city' => $response['name'],
                'temperature' => $response['main']['temp'],
                'description' => $response['weather'][0]['description']
            ]);
        }

        return response()->json(['error' => 'City not found'], 404);
    }
}
