<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use BcMath\Number;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Group as ComponentsGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Metadata\Group;
use Filament\Forms\Set;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Number as SupportNumber;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsGroup::make()->schema([
                    Section::make('Informações do Pedido')
                        ->schema([
                            Select::make('user_id')
                                ->label('Cliente')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('payment_method')
                                ->options([
                                    'cartao' => 'Cartão',
                                    'pix' => 'PIX',
                                    'dinheiro' => 'Dinheiro'
                                ])
                                ->required()
                                ->searchable()
                                ->preload()
                                ->label('Método de Pagamento'),

                            Select::make('payment_status')
                                ->options([
                                    'pendente' => 'Pendente',
                                    'pago' => 'Pago',
                                    'cancelado' => 'Cancelado',

                                ])
                                ->searchable()
                                ->preload()
                                ->label('Status de Pagamento')
                                ->default('pendente')
                                ->required(),


                            ToggleButtons::make('status')
                                ->inline()
                                ->default('novo')
                                ->required()
                                ->options([
                                    'novo' => 'Novo',
                                    'em processamento' => 'Em processamento',
                                    'enviado' => 'Enviado',
                                    'entregue' => 'Entregue',
                                    'cancelado' => 'Cancelado'
                                ])
                                ->colors([
                                    'novo' => 'info',
                                    'em processamento' => 'warning',
                                    'enviado' => 'success',
                                    'entregue' => 'success',
                                    'cancelado' => 'danger'
                                ])
                                ->icons([
                                    'novo' => 'heroicon-m-sparkles',
                                    'em processamento' => 'heroicon-m-arrow-path',
                                    'enviado' => 'heroicon-m-truck',
                                    'entregue' => 'heroicon-m-check-badge',
                                    'cancelado' => 'heroicon-m-x-circle'
                                ]),

                            Select::make('shipping_method')
                                ->options([
                                    'entrega' => 'Entrega - Receba em seu endereço',
                                    'retirada' => 'Retirada - Retire no estabelecimento'
                                ])
                                ->required()
                                ->searchable()
                                ->preload()
                                ->label('Opção de Entrega'),

                            Textarea::make('notes')
                                ->columnSpanFull()
                                ->label('Observações')


                        ])->columns(2),

                    Section::make('Itens do Pedido')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([

                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(3)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0))
                                    ->label('Produto'),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->label('Quantidade')
                                    ->columnSpan(3)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                                TextInput::make('unit_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3)
                                    ->label('Valor Unitário'),


                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3)
                                    ->label('Valor Total'),

                            ])->columns(12)
                            ->label('Item'),

                        Placeholder::make('grand_total_placeholder')
                            ->label('Total Geral')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get("items")) {
                                    return $total;
                                }

                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_amount");
                                }

                                $set('grand_total', $total);
                                return SupportNumber::currency($total, 'BRL');
                            }),

                        Hidden::make('grand_total')
                            ->default(0)
                    ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('grand_total')
                    ->label('Valor Total')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->money('BRL'),


                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable()
                    ->label('Método de Pagamento'),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable()
                    ->label('Status de Pagamento'),


                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable()
                    ->label('Opção de Entrega'),



                SelectColumn::make('status')
                    ->options([
                        'novo' => 'Novo',
                        'em processamento' => 'Em processamento',
                        'enviado' => 'Enviado',
                        'entregue' => 'Entregue',
                        'cancelado' => 'Cancelado'
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criado em'),

                TextColumn::make('update_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualizado em'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() < 10 ? 'danger' : 'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
