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
            'email'         => Auth::user()->email,
            'primary_color' => Auth::user()->primary_color,
            'logo_url'      => Auth::user()->logo_url,
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
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $update = [
            'name'          => $data['name'],
            'email'         => $data['email'],
            'primary_color' => $data['primary_color'],
            'logo_url'      => $data['logo_url'],
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
