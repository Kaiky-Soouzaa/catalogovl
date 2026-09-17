<?php

namespace App\Livewire;

use App\Models\Cart;
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

        $this->carregarCarrinho();

        if ($this->cartItems->isEmpty()) {
            $this->redirect(url($slug . '/carrinho'));
            return;
        }
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

    public function selecionarTipoEntrega(string $tipo): void
    {
        $this->tipoEntrega = $tipo;
    }

    public function irParaPagamento(): void
    {
        if ($this->tipoEntrega === 'entrega') {
            $this->validate([
                'rua'    => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
            ]);
        }

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
        if ($this->cartItems->isEmpty()) {
            $this->redirect(url($this->tenant->slug . '/carrinho'));
            return;
        }

        $this->validate([
            'nome'            => 'required|min:3',
            'telefone'        => 'required|min:8',
            'metodoPagamento' => 'required',
        ]);

        $order = Order::create([
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
