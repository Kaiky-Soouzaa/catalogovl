<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    protected static ?string $title = 'Endereço de entrega';
    protected static ?string $modelLabel = 'endereço';

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome')
                    ->placeholder('Digite seu nome'),

                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->maxLength(20)
                    ->label('Telefone')
                    ->placeholder('(99) 99999-9999'),

                TextInput::make('city')
                    ->required()
                    ->maxLength(255)
                    ->label('Cidade')
                    ->placeholder('Digite a cidade'),

                TextInput::make('state')
                    ->required()
                    ->maxLength(255)
                    ->label('Estado')
                    ->placeholder('Digite o estado'),


                TextInput::make('zip_code')
                    ->required()
                    ->numeric()
                    ->maxLength(10)
                    ->label('CEP')
                    ->placeholder('00000-000'),


                Forms\Components\TextInput::make('street_address')
                    ->required()
                    ->maxLength(255)
                    ->label('Logradouro')
                    ->placeholder('Digite seu endereço'),


                Forms\Components\TextInput::make('reference')
                    ->maxLength(255)
                    ->label('Ponto de Referência')
                    ->placeholder('Ex: Perto de algum estabelecimento'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('street_address')
                    ->label('Logradouro')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
