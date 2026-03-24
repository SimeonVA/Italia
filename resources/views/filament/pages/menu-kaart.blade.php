<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-6 items-start">
        
        <div class="col-span-12 lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($this->pizzas as $pizza)
                <x-filament::section class="h-full !p-4 !rounded-xl shadow-sm border border-gray-100">
                    <div class="flex flex-col h-full justify-between">
                        <div>
                            {{-- Titel met nummer en iconen --}}
                            <div class="flex items-baseline gap-2 mb-2">
                                <h2 class="text-[17px] font-extrabold text-gray-900 leading-tight">
                                    {{ $loop->iteration }}. {{ $pizza['name'] }}
                                </h2>
                                <span class="flex gap-1 text-[10px]">🌾 🥛</span>
                            </div>
                            
                            {{-- Beschrijving --}}
                            <p class="text-gray-500 text-[13px] leading-snug mb-6 line-clamp-3">
                                {{ $pizza['beschrijving'] }}
                            </p>
                        </div>

                        {{-- De Groene Badge Knop (Linksonder) --}}
                        <button wire:click="addToCart({{ $pizza['id'] }})" 
                                class="w-fit flex items-center gap-2 bg-[#A3BC9E] hover:bg-[#8da388] text-white px-3 py-1 rounded-md font-bold text-[14px] transition shadow-sm">
                            € {{ number_format($pizza['prijs'], 2, ',', '.') }} 
                            <x-heroicon-m-plus class="w-4 h-4 text-white/80"/>
                        </button>
                    </div>
                </x-filament::section>
            @endforeach
        </div>

        <div class="col-span-12 lg:col-span-4 sticky top-8">
            <x-filament::section class="!p-0 shadow-xl border-none rounded-xl overflow-hidden">
                {{-- Grijze Hoofdknop bovenaan --}}
                <div class="p-4 bg-white">
                    <button disabled class="w-full bg-[#B0B0B0] text-white font-bold py-4 rounded-lg text-md shadow-inner cursor-not-allowed uppercase tracking-wide">
                        Klik om te bestellen!
                    </button>
                </div>

                {{-- Status sectie --}}
                <div class="px-8 py-10 text-center border-b border-gray-50 bg-white">
                    @if(empty($cart))
                        <p class="font-bold text-[15px] mb-2 text-gray-900 leading-tight">Uw winkelwagen is leeg!</p>
                        <p class="text-[13px] text-gray-500 leading-relaxed px-2">
                            Klik op het icoontje om gerechten aan uw bestelling toe te voegen.
                        </p>
                    @else
                        {{-- Hier komen de items zodra je klikt --}}
                    @endif
                </div>

                {{-- Totaal & Info --}}
                <div class="p-6 bg-white space-y-5">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-700 text-sm uppercase">Totaal</span>
                        <span class="font-bold text-gray-900 text-lg">€ {{ number_format($this->total, 2, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-[12px] text-gray-400 pt-4 border-t border-gray-100">
                        <span>Bezorging vandaag:</span>
                        <span class="font-semibold">16:00 - 21:30</span>
                    </div>
                </div>

                {{-- Footer link --}}
                <div class="bg-gray-50/50 p-4 text-center">
                    <a href="#" class="text-gray-400 text-[12px] font-bold underline decoration-dotted hover:text-primary-500 transition">
                        Wil je liever afhalen?
                    </a>
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>