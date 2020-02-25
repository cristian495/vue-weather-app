<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href="/css/main.css">
        <script src="https://rawgithub.com/darkskyapp/skycons/master/skycons.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>
        <script src="/js/app.js" defer></script>
    </head>
    <body class="bg-blue-200">
        <div id="app" class="flex justify-center p-16">
            <weather-app></weather-app>        
        </div>
    </body>
</html>
