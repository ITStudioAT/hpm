<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/storage/images/favicon.ico">

    <title>Spa Homepage</title>
    @if (!app()->runningUnitTests())
    @vite('resources/js/apps/homepage.js')
    @endif
</head>

<body class="antialiased">

    <div id="app">

    </div>

</body>

</html>