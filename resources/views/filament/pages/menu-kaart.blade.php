<x-filament-panels::page>
    <h1 class="text-2xl font-bold tracking-tight">Menukaart</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($this->pizzas as $pizza)
            <x-filament::section>
                <div class="space-y-2">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-bold text-gray-950 dark:text-white">
                            {{ $pizza['name'] }}
                        </h2>
                        <span class="inline-flex items-center rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-400/10 dark:text-primary-400">
                            € {{ number_format($pizza['prijs'], 2, ',', '.') }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $pizza['beschrijving'] ?? 'Geen beschrijving beschikbaar.' }}
                    </p>
                </div>
            </x-filament::section>
        @empty
            <x-filament::section class="col-span-full flex flex-col items-center justify-center py-12">
                <p class="text-gray-500 italic">Er staan momenteel geen pizza's op de kaart...</p>
            </x-filament::section>
        @endforelse
    </div>
</x-filament-panels::page>