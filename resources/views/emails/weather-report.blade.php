<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Report</title>
</head>

<body>
    <h2 class="dark:text-white text-base font-semibold text-gray-800 mt-4">Weather in {{ $weatherData['name'] }}</h2>
    <p>Temperature: {{ $weatherData['main']['temp'] }}°C</p>
    <p>Weather: {{ $weatherData['weather'][0]['description'] }}</p>
    <p class="dark:text-white text-base font-semibold text-gray-800 mt-4">Clothing Suggestions:</p>
    <p class="dark:text-white text-base font-semibold text-gray-800">{{ $weatherData['suggestions'] }}</p>
</body>

</html>