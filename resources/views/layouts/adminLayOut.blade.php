<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])


    </head>
    <body >
        <div class="antialiased bg-gray-50 dark:bg-gray-900">
            <x-general.header/>
            <x-admin.general.aside/>
            <main class=" md:ml-64 pt-20 h-screen">
                <div class="p-4">
                    ssss
                    @yield("content")
                </div>
            </main>
               
    </body>
    </html>
  </div>