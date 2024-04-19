<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getWeatherData($city)
    {
        $response = Http::get('http://api.openweathermap.org/geo/1.0/direct?q=' . $city . '&limit=5&appid=' . env('OPENWEATHER_API_KEY'));
        if ($response->failed()) {
            Log::info('Failed to fetch weather data. Please try again.');
            return null;
        }

        $data = $response->json();

        if (empty($data)) {
            Log::info('Failed to fetch weather data. Please try again.');
            return null;
        }

        $lat = $data[0]['lat'];
        $lon = $data[0]['lon'];

        $weatherResponse = Http::get('http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid=' . env('OPENWEATHER_API_KEY') . '&units=metric');

        if ($weatherResponse->successful()) {
            return $weatherResponse->json();
        } else {
            Log::info('Failed to fetch weather data. Please try again.');
            return null;
        }
    }

    public function getSuggestions($temperature, $weather)
    {
        $prompt = "Generate a brief suggestion on what to wear today for both men and women based on the weather. The weather is currently {$temperature}°C and {$weather}.";
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        if (!$response) {
            Log::info('Failed to fetch suggestions. Please try again.');
            return null;
        }
        $suggestions = $response->choices[0]->message->content;
        if (empty($suggestions)) {
            Log::info('Failed to fetch suggestions. Please try again.');
            return null;
        }
        return $suggestions;
    }
}
