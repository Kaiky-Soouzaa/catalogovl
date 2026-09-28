<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Cliente;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CheckoutPage extends Component
{
    public User $tenant;

    // Calculados no servidor: o navegador não pode alterar
    #[Locked]
    public $cartItems;

    #[Locked]
    public float $total = 0;

    // Controle de navegação interna (sem redirect entre páginas)
    public string $step = 'agendamento'; // 'agendamento' | 'pagamento'
    public string $tipoEntrega = 'entrega'; // 'entrega' | 'retirada'

    // Pagamento
    public string $tipoPagamento = ''; // ex: 'online' | 'na_entrega'
    public string $metodoPagamento = ''; // ex: 'pix' | 'cartao' | 'dinheiro'

    // Dados do cliente
    public string $nome = '';
    public string $telefone = '';
    public string $email = '';

    // Endereço (só usado se tipoEntrega === 'entrega')
    public string $rua = '';
    public string $numero = '';
    public string $bairro = '';
    public string $cidade = '';
    public string $referencia = '';

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        $cliente = $this->clienteAtual();
        abort_unless($cliente, 403);

        // Preenche com os dados do cadastro (continuam editáveis)
        $this->nome = $cliente->nome;
        $this->telefone = $cliente->telefone ?? '';
        $this->email = $cliente->email;

        $this->carregarCarrinho();

        if ($this->cartItems->isEmpty()) {
            $this->redirect(url($slug));
            return;
        }
    }

    /**
     * Cliente logado E pertencente a esta loja.
     */
    protected function clienteAtual(): ?Cliente
    {
        $cliente = Auth::guard('cliente')->user();

        return $cliente && (int) $cliente->tenant_id === (int) $this->tenant->id
            ? $cliente
            : null;
    }

    protected function carregarCarrinho(): void
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        $this->cartItems = $cart ? $cart->items->load('product') : collect();

        // Quando existir preço por cliente, é aqui que ele entra
        $this->total = $this->cartItems->sum(fn($i) => $i->product->price * $i->quantity);
    }

    protected function regrasEntrega(): array
    {
        $regras = ['tipoEntrega' => 'required|in:entrega,retirada'];

        if ($this->tipoEntrega === 'entrega') {
            $regras += [
                'rua'    => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
            ];
        }

        return $regras;
    }

    public function selecionarTipoEntrega(string $tipo): void
    {
        $this->tipoEntrega = $tipo;
    }

    public function irParaPagamento(): void
    {
        if (! $this->clienteAtual()) {
            $this->redirect(url($this->tenant->slug . '?login=1'));
            return;
        }

        $this->validate($this->regrasEntrega());

        $this->step = 'pagamento';
    }

    public function voltarParaAgendamento(): void
    {
        $this->step = 'agendamento';
    }

    public function selecionarMetodoPagamento(string $metodo): void
    {
        $this->metodoPagamento = $metodo;
    }

    public function confirmar(): void
    {
        $cliente = $this->clienteAtual();

        if (! $cliente) {
            $this->redirect(url($this->tenant->slug . '?login=1'));
            return;
        }

        // Recalcula a partir do banco: não confia no que veio do navegador
        $this->carregarCarrinho();

        if ($this->cartItems->isEmpty()) {
            $this->redirect(url($this->tenant->slug));
            return;
        }

        $this->validate($this->regrasEntrega() + [
            'nome'            => 'required|min:3',
            'telefone'        => 'required|min:8',
            'metodoPagamento' => 'required|in:pix,cartao,dinheiro',
        ]);

        $order = DB::transaction(function () use ($cliente) {
            $order = new Order([
                'user_id'          => $this->tenant->id,
                'session_id'       => session()->getId(),
                'customer_name'    => $this->nome,
                'customer_phone'   => $this->telefone,
                'delivery_type'    => $this->tipoEntrega,
                'delivery_address' => $this->tipoEntrega === 'entrega'
                    ? "{$this->rua}, {$this->numero} - {$this->bairro}, {$this->cidade}"
                    : 'Retirada no estabelecimento',
                'payment_method'   => $this->metodoPagamento,
                'payment_status'   => 'pendente',
                'grand_total'      => $this->total,
                'status'           => 'novo',
                'notes'            => $this->email ?: null,
            ]);

            // Atribuição direta: grava o dono mesmo que cliente_id não esteja no $fillable
            $order->cliente_id = $cliente->id;
            $order->save();

            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'quantity'     => $item->quantity,
                    'unit_amount'  => $item->product->price,
                    'total_amount' => $item->product->price * $item->quantity,
                    'note'         => $item->note,
                ]);
            }

            if ($this->tipoEntrega === 'entrega') {
                Address::create([
                    'order_id'       => $order->id,
                    'phone'          => $this->telefone,
                    'street_address' => "{$this->rua}, {$this->numero}",
                    'city'           => $this->cidade,
                    'reference'      => $this->referencia,
                ]);
            }

            Cart::where('user_id', $this->tenant->id)
                ->where('session_id', session()->getId())
                ->delete();

            return $order;
        });

        // Mantido enquanto a OrdersPage ainda lista pedidos pela sessão
        session()->put('last_order_' . $this->tenant->id, $order->id);
        $pedidos = session()->get('orders_' . $this->tenant->id, []);
        $pedidos[] = $order->id;
        session()->put('orders_' . $this->tenant->id, $pedidos);

        $this->redirect(url($this->tenant->slug . '/pedido/' . $order->id));
    }

    public function render()
    {
        return view('livewire.checkout-page')
            ->layout('components.layouts.checkout', ['tenant' => $this->tenant]);
    }
}
