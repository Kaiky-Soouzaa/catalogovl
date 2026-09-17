<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banners';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banners';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->label('Imagem do banner')
                    ->directory('banners')
                    ->image()
                    ->required()
                    ->helperText('Recomendado: 1200x300px, formato paisagem.'),

                TextInput::make('link')
                    ->label('Link (opcional)')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://...'),

                TextInput::make('order')
                    ->label('Ordem de exibição')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->height(50)
                    ->width(100)
                    ->extraImgAttributes(['style' => 'object-fit: cover; border-radius: 6px;']),

                TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Ativo')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->defaultSort('order')
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
