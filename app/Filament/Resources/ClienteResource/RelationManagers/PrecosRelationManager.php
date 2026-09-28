<?php

namespace App\Filament\Resources\ClienteResource\RelationManagers;

use App\Models\Category;
use App\Models\ClientePreco;
use App\Models\Product;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;

class PrecosRelationManager extends RelationManager
{
    protected static string $relationship = 'precos';

    protected static ?string $title = 'Preços negociados';

    protected static ?string $modelLabel = 'preço';

    protected static ?string $pluralModelLabel = 'preços';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Produto')
                ->options(fn() => Product::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->live()
                ->disabledOn('edit')
                ->unique(
                    ignoreRecord: true,
                    modifyRuleUsing: fn(Unique $rule) => $rule->where('cliente_id', $this->getOwnerRecord()->id)
                )
                ->validationMessages([
                    'unique' => 'Este cliente já tem preço para esse produto. Edite a linha existente.',
                ]),

            Placeholder::make('preco_padrao')
                ->label('Preço padrão do produto')
                ->content(function (Get $get): string {
                    $produto = $get('product_id') ? Product::find($get('product_id')) : null;

                    return $produto
                        ? 'R$ ' . number_format((float) $produto->price, 2, ',', '.')
                        : '—';
                }),

            TextInput::make('price')
                ->label('Preço negociado')
                ->numeric()
                ->prefix('R$')
                ->minValue(0.01)
                ->required(),

            TextInput::make('original_price')
                ->label('Preço de (opcional)')
                ->numeric()
                ->prefix('R$')
                ->minValue(0.01)
                ->gte('price')
                ->helperText('Se preenchido e maior que o preço negociado, o produto aparece como oferta (com preço riscado e selo de desconto) só para este cliente. Deixe vazio para um preço de contrato, sem promoção.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.price')
                    ->label('Preço padrão')
                    ->formatStateUsing(fn($state) => filled($state) ? 'R$ ' . number_format((float) $state, 2, ',', '.') : null),

                TextColumn::make('price')
                    ->label('Preço negociado')
                    ->formatStateUsing(fn($state) => filled($state) ? 'R$ ' . number_format((float) $state, 2, ',', '.') : null)
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('original_price')
                    ->label('Preço de')
                    ->formatStateUsing(fn($state) => filled($state) ? 'R$ ' . number_format((float) $state, 2, ',', '.') : null)
                    ->placeholder('—'),

                TextColumn::make('diferenca')
                    ->label('Vs. padrão')
                    ->badge()
                    ->state(function (ClientePreco $record): ?string {
                        $padrao = (float) ($record->product?->price ?? 0);

                        if ($padrao <= 0) {
                            return null;
                        }

                        $pct = (int) round((((float) $record->price - $padrao) / $padrao) * 100);

                        return $pct === 0 ? '0%' : ($pct > 0 ? '+' . $pct . '%' : $pct . '%');
                    })
                    ->color(fn(?string $state): string => match (true) {
                        $state === null => 'gray',
                        str_starts_with($state, '-') => 'success',
                        str_starts_with($state, '+') => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('updated_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar preço')
                    ->modalHeading('Adicionar preço negociado'),

                Tables\Actions\Action::make('descontoEmMassa')
                    ->label('Aplicar desconto em massa')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('gray')
                    ->modalHeading('Aplicar desconto em massa')
                    ->modalDescription('O desconto é calculado sobre o preço padrão e substitui o preço negociado que este cliente já tem nos produtos escolhidos.')
                    ->modalSubmitActionLabel('Aplicar')
                    ->form([
                        Radio::make('alvo')
                            ->label('Aplicar em')
                            ->options([
                                'todos'     => 'Todos os produtos ativos',
                                'categoria' => 'Uma categoria',
                                'produtos'  => 'Produtos que eu escolher',
                            ])
                            ->default('todos')
                            ->live()
                            ->required(),

                        Select::make('category_id')
                            ->label('Categoria')
                            ->options(fn() => Category::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn(Get $get) => $get('alvo') === 'categoria')
                            ->required(fn(Get $get) => $get('alvo') === 'categoria'),

                        Select::make('product_ids')
                            ->label('Produtos')
                            ->multiple()
                            ->options(fn() => Product::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn(Get $get) => $get('alvo') === 'produtos')
                            ->required(fn(Get $get) => $get('alvo') === 'produtos'),

                        TextInput::make('percentual')
                            ->label('Desconto')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0.01)
                            ->maxValue(99.99)
                            ->required(),

                        Toggle::make('mostrar_como_oferta')
                            ->label('Mostrar como oferta')
                            ->helperText('Desligado: o cliente apenas paga menos, sem selo. Ligado: aparece o preço original riscado com selo de desconto, e o produto entra em "Produtos em oferta" para ele.')
                            ->default(false),
                    ])
                    ->action(function (array $data): void {
                        $cliente = $this->getOwnerRecord();

                        $produtos = Product::where('is_active', true);

                        if ($data['alvo'] === 'categoria') {
                            $produtos->where('category_id', $data['category_id']);
                        }

                        if ($data['alvo'] === 'produtos') {
                            $produtos->whereIn('id', $data['product_ids']);
                        }

                        $fator = 1 - ((float) $data['percentual'] / 100);
                        $agora = now();

                        $linhas = $produtos->get(['id', 'price'])
                            ->map(fn(Product $p) => [
                                'cliente_id'     => $cliente->id,
                                'product_id'     => $p->id,
                                'price'          => max(0.01, round((float) $p->price * $fator, 2)),
                                'original_price' => $data['mostrar_como_oferta'] ? $p->price : null,
                                'created_at'     => $agora,
                                'updated_at'     => $agora,
                            ])
                            ->all();

                        if (count($linhas) === 0) {
                            Notification::make()
                                ->title('Nenhum produto encontrado para aplicar o desconto')
                                ->warning()
                                ->send();

                            return;
                        }

                        DB::transaction(function () use ($linhas) {
                            foreach (array_chunk($linhas, 500) as $lote) {
                                ClientePreco::upsert(
                                    $lote,
                                    ['cliente_id', 'product_id'],
                                    ['price', 'original_price', 'updated_at']
                                );
                            }
                        });

                        Notification::make()
                            ->title('Desconto aplicado em ' . count($linhas) . ' produto(s)')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->label('Editar'),

                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remover preços selecionados'),
                ]),
            ]);
    }
}
