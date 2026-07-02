<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.edit-profile';
    protected static ?string $navigationLabel = 'Meu Perfil';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'name'          => Auth::user()->name,
            'slug'          => Auth::user()->slug,
            'email'         => Auth::user()->email,
            'primary_color' => Auth::user()->primary_color,
            'logo_url'      => Auth::user()->logo_url,
            'store_name'    => Auth::user()->store_name,
            'whatsapp'      => Auth::user()->whatsapp,
            'address'       => Auth::user()->address,
            'neighborhood'  => Auth::user()->neighborhood,
            'city'          => Auth::user()->city,
            'state'         => Auth::user()->state,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações pessoais')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('Nova senha')
                            ->password()
                            ->nullable()
                            ->minLength(8),
                    ]),

                Section::make('Identidade visual')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Cor principal (botões)'),
                        FileUpload::make('logo_url')
                            ->label('Logo do catálogo')
                            ->image()
                            ->disk('public')
                            ->directory('logos'),
                    ]),

                Section::make('Informações da empresa')
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Nome da loja')
                            ->placeholder('Ex: Mercado Parceiro')
                            ->maxLength(255),

                        TextInput::make('whatsapp')
                            ->label('WhatsApp (com DDD)')
                            ->placeholder('Ex: 5562999999999')
                            ->helperText('Somente números. Ex: 5562999999999')
                            ->maxLength(20),


                        TextInput::make('slug')
                            ->label('Slug da loja (URL)')
                            ->placeholder('Ex: mercado-parceiro')
                            ->helperText('Usado na URL da sua loja. Apenas letras minúsculas, números e hífens.')
                            ->unique('users', 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->required(),


                        TextInput::make('address')
                            ->label('Endereço')
                            ->placeholder('Ex: Rua Milton Ferreira, 00')
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make('neighborhood')
                            ->label('Bairro')
                            ->placeholder('Ex: Centro')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->label('Cidade')
                            ->placeholder('Ex: Ceres')
                            ->maxLength(255),

                        TextInput::make('state')
                            ->label('Estado (UF)')
                            ->placeholder('Ex: GO')
                            ->maxLength(2)
                            ->minLength(2),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $update = [
            'name'          => $data['name'],
            'slug'          => $data['slug'],
            'email'         => $data['email'],
            'primary_color' => $data['primary_color'],
            'logo_url'      => $data['logo_url'],
            'store_name'    => $data['store_name'],
            'whatsapp'      => $data['whatsapp'],
            'address'       => $data['address'],
            'neighborhood'  => $data['neighborhood'],
            'city'          => $data['city'],
            'state'         => $data['state'],
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        $user->update($update);

        Notification::make()
            ->title('Perfil atualizado com sucesso!')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Meu Perfil';
    }
}
