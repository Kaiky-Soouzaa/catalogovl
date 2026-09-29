@php
    $primaryColor = $tenant->primary_color ?? '00b050';
    $logo = $tenant->logo_url ? Storage::url($tenant->logo_url) : asset('assets/images/vl-sistemas.jpeg');

@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Catalogo-vl' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --primary: {{ $primaryColor }};
        }

        .btn-primary {
            background-color: var(--primary);
        }
    </style>

</head>

<body class="flex flex-col min-h-screen">

    @livewire('partials.navbar')

    {{-- <header style="background-color: var(--primary);" class="p-4">
        <img src="{{ $logo }}" alt="Logo" style="height: 2.5rem; object-fit: contain;">
    </header> --}}

    <main class="flex-1">
        {{ $slot }}
    </main>

    @livewire('partials.footer')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
</body>

</html>
