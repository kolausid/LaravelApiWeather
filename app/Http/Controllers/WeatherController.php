<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class WeatherController extends Controller
{
    public function getWeather(Request $request, $city) {
        // Валидация входных данных
        $request->validate([
            'units' => 'nullable|in:metric,imperial,standard',
        ]);

        $units = $request->input('units', 'metric'); // по умолчанию — metric
        $apiKey = config('app.openweather_api_key');
        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ru'
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $data = $response->json();

        return response()->json([
            'city'        => $data['name'],
            'temperature' => $data['main']['temp'],
            'description' => $data['weather'][0]['description'],
            'humidity'    => $data['main']['humidity'] . '%',
            'pressure'    => $data['main']['pressure'] . ' гПа',
            'wind_speed'  => $data['wind']['speed'] . ($units === 'imperial' ? ' миль/ч' : ' м/с'),
            'precipitation_probability' => $data['rain']['1h'] ?? 'Нет данных',
            'units'       => $units,
        ]);
    }
}
