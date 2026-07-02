<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Produtos';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $pluralModelLabel = 'Produtos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Produto')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(230)
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
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ->label('Slug'),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')
                            ->label('Descrição'),

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

                        TextInput::make('barcode')
                            ->label('Código Produto')
                            ->maxLength(20)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ex: 7891234567890')
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
                            ->label('Preço Original')
                            ->helperText('Preencha apenas se o produto estiver em oferta.')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Status')
                    ->schema([
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
                    ->columns(4),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Pesquisar produtos...')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->icon('heroicon-m-funnel')
                    ->color('gray')
            )
            ->toggleColumnsTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('')
                    ->icon('heroicon-m-view-columns')
                    ->color('gray')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('')
                    ->height(60)
                    ->width(60)
                    ->visibleFrom('md')
                    ->extraImgAttributes([
                        'style' => 'object-fit: contain; padding: 3px; background: #fff; border-radius: 10px;',
                    ]),

                TextColumn::make('name')
                    ->searchable()
                    ->label('Produto')
                    ->weight('semibold')
                    ->limit(24)
                    ->wrap()
                    ->grow(),

                TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->label('Categoria')
                    ->visibleFrom('md')
                    ->toggleable(),

                TextColumn::make('brand.name')
                    ->sortable()
                    ->label('Marca')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price')
                    ->money('BRL')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->label('Preço'),

                TextColumn::make('original_price')
                    ->money('BRL')
                    ->sortable()
                    ->color('gray')
                    ->label('Preço Original')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('barcode')
                    ->label('Código')
                    ->copyable()
                    ->copyMessage('Código copiado')
                    ->limit(8)
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Destaque')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->visibleFrom('lg'),

                IconColumn::make('on_sale')
                    ->boolean()
                    ->label('À Venda')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->visibleFrom('md'),

                IconColumn::make('in_stock')
                    ->boolean()
                    ->label('Estoque')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->visibleFrom('md'),
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
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->label('Editar'),

                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionados'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
