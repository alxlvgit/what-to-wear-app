<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Report</title>
</head>

<body>
    <h1>Weather Report</h1>
    <h2>Weather in {{ $weatherData['name'] }}</h2>
    <p>Temperature: {{ $weatherData['main']['temp'] }}°C</p>
    <p>Weather: {{ $weatherData['weather'][0]['description'] }}</p>
</body>

</html>