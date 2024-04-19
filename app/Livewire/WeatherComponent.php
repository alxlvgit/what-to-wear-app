<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\WeatherController;


class WeatherComponent extends Component
{
    public $weatherData;
    public $city;
    public $temperature;
    public $weather;
    public $suggestions;
    public $errorMessage;

    public function getWeatherSuggestions(WeatherController $weatherController)
    {
        $weatherData = $weatherController->getWeatherData($this->city);
        if (!$weatherData) {
            $this->errorMessage = 'Failed to fetch weather data. Please try again.';
            return;
        }

        $this->weatherData = $weatherData;
        $this->temperature = $this->weatherData['main']['temp'];
        $this->weather = $this->weatherData['weather'][0]['description'];
        $this->errorMessage = '';
        $this->suggestions = $weatherController->getSuggestions($this->temperature, $this->weather);
    }

    public function render()
    {
        return view('livewire.weather-component');
    }
}
