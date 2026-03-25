<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; align-items: start;">

        <div style="grid-column: span 2; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            @foreach ($this->pizzas as $pizza)
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.07); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 220px;">

                    {{-- Afbeelding --}}
                    @if($pizza['image'])
                        <img
                            src="{{ Storage::url($pizza['image']) }}"
                            alt="{{ $pizza['name'] }}"
                            style="width: 100%; height: 160px; object-fit: cover;"
                        />
                    @else
                        <div style="width: 100%; height: 160px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 11px; color: #9ca3af;">Geen afbeelding</span>
                        </div>
                    @endif

                    {{-- Tekst & knop --}}
                    <div style="padding: 16px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
                        <div>
                            <p style="font-size: 16px; font-weight: 800; color: #111827; margin: 0 0 6px 0;">
                                {{ $loop->iteration }}. {{ $pizza['name'] }}
                            </p>
                            <p style="font-size: 13px; color: #9ca3af; line-height: 1.5; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $pizza['beschrijving'] }}
                            </p>
                        </div>

                        <button
                            wire:click="addToCart({{ $pizza['id'] }})"
                            style="background: #E05A4E; color: white; font-size: 13px; font-weight: 700; padding: 9px 18px; border-radius: 6px; border: none; cursor: pointer; width: fit-content;"
                            onmouseover="this.style.background='#c94d42'"
                            onmouseout="this.style.background='#E05A4E'"
                        >
                            € {{ number_format($pizza['prijs'], 2, ',', '.') }} +
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <div style="position: sticky; top: 32px;">
            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); overflow: hidden;">

                <div style="padding: 16px; border-bottom: 1px solid #f3f4f6;">
                    <button
                        style="width: 100%; padding: 12px; border-radius: 8px; border: none; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; cursor: {{ empty($cart) ? 'not-allowed' : 'pointer' }}; background: {{ empty($cart) ? '#d1d5db' : '#E05A4E' }}; color: white;">
                        Klik om te bestellen!
                    </button>
                </div>

                <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6; min-height: 90px;">
                    @if(empty($cart))
                        <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 0 0 6px 0;">Uw winkelwagen is leeg!</p>
                        <p style="font-size: 11px; color: #9ca3af; line-height: 1.5; margin: 0;">
                            Klik op het icoontje om gerechten aan uw bestelling toe te voegen.
                        </p>
                    @else
                        @foreach($cart as $id => $item)
                            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px;">
                                <span style="color: #374151;">{{ $item['quantity'] }}× {{ $item['name'] }}</span>
                                <span style="font-weight: 700; color: #111827;">€ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div style="padding: 16px 24px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Totaal</span>
                    <span style="font-size: 18px; font-weight: 700; color: #111827;">€ {{ number_format($this->total, 2, ',', '.') }}</span>
                </div>

                <div style="padding: 12px 24px; display: flex; justify-content: space-between; font-size: 11px; color: #9ca3af;">
                    <span>Bezorging vandaag:</span>
                    <span style="font-weight: 600;">16:00 – 21:30</span>
                </div>

                <div style="padding: 12px 24px; text-align: center; background: #f9fafb;">
                    <a href="#" style="font-size: 11px; font-weight: 700; color: #9ca3af; text-decoration: underline; text-decoration-style: dotted;">
                        Wil je liever afhalen?
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::page>