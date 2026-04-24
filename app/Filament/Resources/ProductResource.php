<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group as ComponentsGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Mail\Markdown;
use Filament\Forms\Set;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Produtos';
    protected static ?string $modelLabel = 'Produto';
    protected static ?string $pluralModelLabel = 'Produto';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Produto')->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nome')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation !== 'create') {
                                return;
                            }

                            $set('slug', Str::slug($state));
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->disabled()
                        ->dehydrated()
                        ->unique(Product::class, 'slug', ignoreRecord: true),

                    MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('products')
                        ->label('Descrição')
                        ->columnSpan(2),

                    FileUpload::make('images')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                        ->label('Imagem')
                        ->columnSpan(2),

                    Select::make('category_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('category', 'name')
                        ->label('Categoria')
                        ->columnSpan(2),

                    Select::make('brand_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('brand', 'name')
                        ->label('Marca')
                        ->columnSpan(2),


                    TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->prefix('R$')
                        ->label('Preço')
                        ->columnSpan(2),


                    TextInput::make('original_price')
                        ->numeric()
                        ->prefix('R$')
                        ->label('Preço Original (antes do desconto)')
                        ->helperText('Preencha apenas se o produto estiver em oferta. Ex: preço era R$28,99, agora é R$23,99')
                        ->columnSpan(2),

                ])->columns(2),


                Section::make('Status')->schema([
                    Toggle::make('in_stock')
                        ->required()
                        ->default(true)
                        ->label('Em Estoque'),


                    Toggle::make('is_active')
                        ->required()
                        ->default(true)
                        ->label('Ativo'),

                    Toggle::make('is_featured')
                        ->required()
                        ->label('Produto em Destaque'),

                    Toggle::make('on_sale')
                        ->required()
                        ->default(true)
                        ->label('À Venda'),

                ])

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),

                TextColumn::make('category.name')
                    ->sortable()
                    ->label('Categoria'),


                TextColumn::make('brand.name')
                    ->sortable()
                    ->label('Marca'),

                TextColumn::make('price')
                    ->money('BRL')
                    ->sortable()
                    ->label('Preço'),

                TextColumn::make('original_price')
                    ->money('BRL')
                    ->sortable()
                    ->label('Preço Original'),


                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Em Destaque'),

                IconColumn::make('on_sale')
                    ->boolean()
                    ->label('À Venda'),

                IconColumn::make('in_stock')
                    ->boolean()
                    ->label('Em Estoque'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Ativo'),

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

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Categoria'),


                SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->label('Marca'),


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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
