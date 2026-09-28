<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $modelLabel = 'Cliente';

    protected static ?string $pluralModelLabel = 'Clientes';

    protected static ?int $navigationSort = 3;

    // Os clientes se cadastram sozinhos pela loja; aqui só se edita e define preços
    public static function canCreate(): bool
    {
        return false;
    }

    // Cada lojista enxerga apenas os clientes da própria loja
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tenant_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('tipo')
                ->label('Tipo')
                ->options([
                    'pf' => 'Pessoa Física',
                    'pj' => 'Pessoa Jurídica',
                ])
                ->required(),

            TextInput::make('nome')
                ->label('Nome / Razão social')
                ->required()
                ->maxLength(255),

            TextInput::make('cpf_cnpj')
                ->label('CPF / CNPJ')
                ->required()
                ->maxLength(20)
                ->unique(
                    ignoreRecord: true,
                    modifyRuleUsing: fn(Unique $rule) => $rule->where('tenant_id', auth()->id())
                ),

            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(
                    ignoreRecord: true,
                    modifyRuleUsing: fn(Unique $rule) => $rule->where('tenant_id', auth()->id())
                ),

            TextInput::make('telefone')
                ->label('Telefone')
                ->maxLength(20),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome / Razão social')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => $state === 'pf' ? 'Pessoa Física' : 'Pessoa Jurídica')
                    ->color(fn(string $state): string => $state === 'pf' ? 'info' : 'warning'),

                TextColumn::make('cpf_cnpj')
                    ->label('CPF / CNPJ')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),

                TextColumn::make('precos_count')
                    ->counts('precos')
                    ->label('Preços negociados')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'pf' => 'Pessoa Física',
                        'pj' => 'Pessoa Jurídica',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->label('Dados e preços'),

                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->label('Excluir'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PrecosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'edit'  => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
