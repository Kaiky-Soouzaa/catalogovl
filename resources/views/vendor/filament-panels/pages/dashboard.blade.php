<x-filament-panels::page class="fi-dashboard-page">

    @php
        $hour = now()->hour;

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Bom dia';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'Boa tarde';
        } else {
            $greeting = 'Boa noite';
        }

        $firstName = explode(' ', auth()->user()->name)[0];
    @endphp

    <div style="margin-bottom: 5px;">


        <p class="mt-0 inline-flex items-center gap-1 text-lg text-gray-600 whitespace-nowrap">
            <span>{{ $greeting }},</span>

            <span class="font-semibold text-gray-900">
                {{ $firstName }}
            </span>
        </p>


    </div>

    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()" />

</x-filament-panels::page>
