<div class="px-8">
    <form wire:submit.prevent="getWeather" class="flex flex-col">
        @csrf
        <label for="city" class="dark:text-white mt-2">Enter City Name:</label>
        <input type="text" class="mt-2" wire:model="city" required>
        <button type="submit" class="text-white rounded mt-4 px-5 py-2 bg-blue-500">Get Clothing Suggestions</button>
    </form>

    @if($weatherData)
    <div class="dark:text-white text-gray-800 mt-4">
        <h2>Weather in {{ $weatherData['name'] }}</h2>
        <p>Temperature: {{ $weatherData['main']['temp'] }}°C</p>
        <p>Weather: {{ $weatherData['weather'][0]['main'] }} , {{ $weatherData['weather'][0]['description'] }}</p>

        @if($suggestions)
        <div class="dark:text-white text-gray-800 mt-4">
            <h2>Clothing Suggestions:</h2>
            <p>{{ $suggestions }}</p>
        </div>
        @endif
    </div>
    @endif

    @if($errorMessage)
    <div>
        <p>{{ $errorMessage }}</p>
    </div>
    @endif
</div>