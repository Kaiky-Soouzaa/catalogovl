<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{

    protected array|string|int $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '2s';

    public function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->heading('Últimos Pedidos')
            ->defaultSort('created_at', 'desc')
            ->columns([


                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),


                TextColumn::make('grand_total')
                    ->money('BRL')
                    ->label('Valor Total'),


                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'novo' => 'info',
                        'em processamento' => 'warning',
                        'enviado' => 'success',
                        'entregue' => 'success',
                        'cancelado' => 'danger'
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'novo' => 'heroicon-m-sparkles',
                        'em processamento' => 'heroicon-m-arrow-path',
                        'enviado' => 'heroicon-m-truck',
                        'entregue' => 'heroicon-m-check-badge',
                        'cancelado' => 'heroicon-m-x-circle'
                    })
                    ->label('Status do Pedido')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable()
                    ->label('Método de Pagamento'),

                TextColumn::make('payment_status')
                    ->sortable()
                    ->badge()
                    ->searchable()
                    ->label('Status de Pagamento'),


                TextColumn::make('created_at')
                    ->label('Data do Pedido')
                    ->dateTime()



            ])

            ->actions([
                Action::make('Ver')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
