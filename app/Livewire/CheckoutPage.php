<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\User;
use Livewire\Component;

class CheckoutPage extends Component
{
    public User $tenant;
    public $cartItems;
    public float $total = 0;

    // Etapas: entrega → pagamento → metodo_pagamento → dados → confirmado
    public string $etapa = 'entrega';

    // Entrega
    public string $tipoEntrega = '';

    // Pagamento
    public string $tipoPagamento = '';
    public string $metodoPagamento = '';

    // Dados do cliente
    public string $nome = '';
    public string $telefone = '';
    public string $email = '';
    public string $cpf = '';

    // Endereço (só se entrega)
    public string $rua = '';
    public string $numero = '';
    public string $bairro = '';
    public string $cidade = '';
    public string $referencia = '';

    public function mount(int $userId): void
    {
        $this->tenant = User::findOrFail($userId);
        app()->instance('tenant', $this->tenant);
        $this->carregarCarrinho();
    }

    public function carregarCarrinho(): void
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        $this->cartItems = $cart ? $cart->items->load('product') : collect();
        $this->total = $this->cartItems->sum(fn($i) => $i->product->price * $i->quantity);
    }

    public function selecionarEntrega(string $tipo): void
    {
        $this->tipoEntrega = $tipo;
        $this->etapa = 'pagamento';
    }

    public function selecionarTipoPagamento(string $tipo): void
    {
        $this->tipoPagamento = $tipo;
        $this->etapa = 'metodo_pagamento';
    }

    public function selecionarMetodoPagamento(string $metodo): void
    {
        $this->metodoPagamento = $metodo;
        $this->etapa = 'dados';
    }

    public function voltar(): void
    {
        $this->etapa = match ($this->etapa) {
            'pagamento'        => 'entrega',
            'metodo_pagamento' => 'pagamento',
            'dados'            => 'metodo_pagamento',
            default            => 'entrega',
        };
    }

    public function confirmar(): void
    {
        $this->validate([
            'nome'     => 'required|min:3',
            'telefone' => 'required|min:8',
        ]);

        if ($this->tipoEntrega === 'entrega') {
            $this->validate([
                'rua'    => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
            ]);
        }

        // Cria o pedido
        $order = Order::create([
            'user_id'          => $this->tenant->id,
            'session_id'       => session()->getId(),
            'customer_name'    => $this->nome,
            'customer_phone'   => $this->telefone,
            'delivery_type'    => $this->tipoEntrega,
            'delivery_address' => $this->tipoEntrega === 'entrega'
                ? "{$this->rua}, {$this->numero} - {$this->bairro}, {$this->cidade}"
                : "retirada",
            'payment_method'   => $this->metodoPagamento,
            'payment_status'   => 'pendente',
            'grand_total'      => $this->total,
            'status'           => 'novo',
            'notes'            => $this->email ?: null,
        ]);

        // Cria os itens do pedido
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

        // Salva endereço se for entrega
        if ($this->tipoEntrega === 'entrega') {
            Address::create([
                'order_id'       => $order->id,
                'phone'          => $this->telefone,
                'street_address' => "{$this->rua}, {$this->numero}",
                'city'           => $this->cidade,
                'reference'      => $this->referencia,
            ]);
        }

        // Limpa o carrinho
        Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->delete();

        // Após criar o pedido, salva na sessão
        session()->put('last_order_' . $this->tenant->id, $order->id);

        // Também salva lista de pedidos do cliente
        $pedidos = session()->get('orders_' . $this->tenant->id, []);
        $pedidos[] = $order->id;
        session()->put('orders_' . $this->tenant->id, $pedidos);


        // Redireciona para página do pedido
        $this->redirect(url($this->tenant->id . '/pedido/' . $order->id));
    }

    public function render()
    {
        return view('livewire.checkout-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
