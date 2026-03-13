<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pizza;

class MenuKaart extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text'; 
    protected static ?string $navigationLabel = 'Menukaart';
    protected static ?string $slug = 'menu-kaart';
    protected string $view = 'filament.pages.menu-kaart';

    public array $pizzas = [];

    public function mount(): void
    {
        $this->pizzas = Pizza::where('status', 'op-voorraad')->get()->toArray();
    }
}