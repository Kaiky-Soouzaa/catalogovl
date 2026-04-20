<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         OrderStats::class
    //     ];
    // }


    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos os Pedidos'),
            'novo' => Tab::make('Novos Pedidos')->query(fn($query) => $query->where('status', 'novo')),
            'em processamento' => Tab::make('Pedidos em Processamento')->query(fn($query) => $query->where('status', 'em processamento')),
            'enviado' => Tab::make('Pedidos Enviados')->query(fn($query) => $query->where('status', 'enviado')),
            'cancelado' => Tab::make('Pedidos Cancelados')->query(fn($query) => $query->where('status', 'cancelado')),
        ];
    }
}
