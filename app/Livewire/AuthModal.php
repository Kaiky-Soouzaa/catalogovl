<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class AuthModal extends Component
{
    public int $tenantId;
    public string $primaryColor = '#d97706';
    public string $logoUrl = '';

    public bool $open = false;
    public string $step = 'check'; // check | login | register-credentials | register-details

    // Step check
    public string $identificador = '';

    // Step login
    public string $senha = '';

    // Step register-credentials
    public string $email = '';
    public string $novaSenha = '';
    public string $confirmarSenha = '';

    // Step register-details
    public string $tipo = 'pf'; // pf | pj
    public string $nome = '';
    public string $cpfCnpj = '';
    public string $telefone = '';
    public string $dataNascimento = '';
    public string $sexo = '';
    public bool $aceitouTermos = false;

    public function mount(int $tenantId, string $primaryColor = '#d97706', string $logoUrl = ''): void
    {
        $this->tenantId = $tenantId;
        $this->primaryColor = $primaryColor;
        $this->logoUrl = $logoUrl;

        // Visitante barrado pelo middleware chega na home com ?login=1
        if (request()->boolean('login')) {
            $this->open = true;
        }
    }

    #[On('abrir-modal-login')]
    public function abrir()
    {
        $this->reset([
            'step',
            'identificador',
            'senha',
            'email',
            'novaSenha',
            'confirmarSenha',
            'nome',
            'cpfCnpj',
            'telefone',
            'dataNascimento',
            'sexo',
            'aceitouTermos',
        ]);
        $this->step = 'check';
        $this->open = true;
    }

    public function fechar()
    {
        $this->open = false;
    }

    public function verificarIdentificador()
    {
        $this->validate(['identificador' => 'required|string']);

        $existe = Cliente::where('tenant_id', $this->tenantId)
            ->where(function ($q) {
                $q->where('email', $this->identificador)
                    ->orWhere('cpf_cnpj', $this->identificador);
            })
            ->exists();

        if ($existe) {
            $this->step = 'login';
            return;
        }

        // Pré-preenche o próximo step com o que a pessoa já digitou
        if (str_contains($this->identificador, '@')) {
            $this->email = $this->identificador;
        } else {
            $this->cpfCnpj = $this->identificador;
        }

        $this->step = 'register-credentials';
    }

    public function voltarParaCheck()
    {
        $this->step = 'check';
    }

    public function login()
    {
        $this->validate(['senha' => 'required']);

        $cliente = Cliente::where('tenant_id', $this->tenantId)
            ->where(function ($q) {
                $q->where('email', $this->identificador)
                    ->orWhere('cpf_cnpj', $this->identificador);
            })
            ->first();

        if (! $cliente || ! Hash::check($this->senha, $cliente->password)) {
            $this->addError('senha', 'Senha incorreta.');
            return;
        }

        Auth::guard('cliente')->login($cliente, remember: true);
        session()->regenerate();

        $this->redirecionarAposLogin();
    }

    public function avancarCredenciais()
    {
        $this->validate([
            'email' => 'required|email',
            'novaSenha' => 'required|min:6|same:confirmarSenha',
        ], [
            'novaSenha.same' => 'As senhas não coincidem.',
        ]);

        $existeEmail = Cliente::where('tenant_id', $this->tenantId)
            ->where('email', $this->email)
            ->exists();

        if ($existeEmail) {
            $this->addError('email', 'Já existe um cadastro com esse e-mail.');
            return;
        }

        $this->step = 'register-details';
    }

    public function voltarParaCredenciais()
    {
        $this->step = 'register-credentials';
    }

    public function finalizarCadastro()
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'cpfCnpj' => 'required|string|max:20',
            'telefone' => 'nullable|string|max:20',
            'dataNascimento' => 'nullable|date',
            'sexo' => 'nullable|string',
            'aceitouTermos' => 'accepted',
        ], [
            'aceitouTermos.accepted' => 'Você precisa aceitar os termos de uso.',
        ]);

        $existeDoc = Cliente::where('tenant_id', $this->tenantId)
            ->where('cpf_cnpj', $this->cpfCnpj)
            ->exists();

        if ($existeDoc) {
            $this->addError('cpfCnpj', 'Já existe um cadastro com esse documento.');
            return;
        }

        $cliente = Cliente::create([
            'tenant_id' => $this->tenantId,
            'tipo' => $this->tipo,
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf_cnpj' => $this->cpfCnpj,
            'telefone' => $this->telefone,
            'data_nascimento' => $this->dataNascimento ?: null,
            'sexo' => $this->sexo,
            'password' => Hash::make($this->novaSenha),
        ]);

        Auth::guard('cliente')->login($cliente, remember: true);
        session()->regenerate();

        $this->redirecionarAposLogin();
    }

    /**
     * Volta para a página que o middleware barrou; se não houver,
     * recarrega a página atual sem a query string (?login=1).
     */
    protected function redirecionarAposLogin(): void
    {
        $destino = session()->pull('cliente.intended');

        if (! $destino) {
            $referer = request()->header('Referer') ?: url('/');
            $destino = strtok($referer, '?');
        }

        $this->open = false;
        $this->dispatch('cliente-logado');
        $this->redirect($destino, navigate: false);
    }

    public function render()
    {
        return view('livewire.auth-modal');
    }
}
