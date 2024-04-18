<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;


class WeatherComponent extends Component
{
    public $weatherData;
    public $city;
    public $temperature;
    public $weather;
    public $suggestions;
    public $errorMessage;

    public function getWeather()
    {
        $response = Http::get('http://api.openweathermap.org/geo/1.0/direct?q=' . $this->city . '&limit=5&appid=' . env('OPENWEATHER_API_KEY'));
        if ($response->failed()) {
            $this->errorMessage = 'Failed to fetch weather data. Please try again.';
            return;
        }

        $data = $response->json();

        if (empty($data)) {
            $this->errorMessage = 'Failed to fetch weather data. Please try again.';
            return;
        }

        $lat = $data[0]['lat'];
        $lon = $data[0]['lon'];

        $weatherResponse = Http::get('http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid=' . env('OPENWEATHER_API_KEY') . '&units=metric');

        if ($weatherResponse->successful()) {
            $this->weatherData = $weatherResponse->json();
            $this->temperature = $this->weatherData['main']['temp'];
            $this->weather = $this->weatherData['weather'][0]['description'];
            $this->errorMessage = '';
            $this->getSuggestions();
        } else {
            $this->errorMessage = 'Failed to fetch weather data. Please try again.';
        }
    }



    private function getSuggestions()
    {
        $prompt = "Generate a brief suggestion on what to wear today for both men and women based on the weather. The weather is currently {$this->temperature}°C and {$this->weather}.";
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        if (!$response) {
            $this->errorMessage = 'Failed to fetch suggestions. Please try again.';
            return;
        }
        $suggestions = $response->choices[0]->message->content;
        if (empty($suggestions)) {
            $this->errorMessage = 'Failed to fetch suggestions. Please try again.';
            return;
        }
        $this->suggestions = $suggestions;
    }

    public function render()
    {
        return view('livewire.weather-component');
    }
}
