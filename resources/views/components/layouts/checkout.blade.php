@php
    $primaryColor = $tenant->primary_color ?? '00b050';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Finalizar pedido' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --primary: {{ $primaryColor }};
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <main class="flex-1">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
