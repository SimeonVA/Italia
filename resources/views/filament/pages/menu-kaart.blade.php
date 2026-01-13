<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">Menukaart</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($this->pizzas as $pizza)
            <div class="p-4 bg-white rounded-lg shadow">
                <h2 class="font-semibold text-lg">{{ $pizza['name'] }}</h2>
                <p class="text-gray-600">{{ $pizza['beschrijving'] }}</p>
                <p class="mt-2 font-bold">Prijs: € {{ $pizza['prijs'] }}</p>
            </div>
        @endforeach
    </div>
</x-filament::page>
