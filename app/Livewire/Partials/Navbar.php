<?php

namespace App\Livewire\Partials;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\PrecoCliente;

class Navbar extends Component
{
    public string $busca = '';
    public bool $mostrarResultados = false;
    public ?User $tenant = null;

    public int $totalItens = 0;
    public float $totalValor = 0;
    public bool $carrinhoAberto = false;
    public array $cartItensDetalhe = [];

    // Decidido uma única vez no mount(): nas atualizações do Livewire a
    // requisição é /livewire/update, então request()->is() não serve no blade.
    public bool $mostrarCategorias = false;

    protected $listeners = [
        'cartUpdated'         => 'atualizarCarrinho',
        'abrirCarrinhoGlobal' => 'abrirCarrinho',
    ];

    public function mount(): void
    {
        try {
            $this->tenant = app('tenant');
        } catch (\Exception $e) {
            $this->tenant = null;
        }

        $this->mostrarCategorias = $this->tenant !== null
            && request()->is($this->tenant->slug);

        $this->atualizarCarrinho();
    }

    /**
     * Cliente logado E pertencente a esta loja.
     */
    protected function clienteLogado(): bool
    {
        $cliente = Auth::guard('cliente')->user();

        return $this->tenant !== null
            && $cliente !== null
            && (int) $cliente->tenant_id === (int) $this->tenant->id;
    }

    /**
     * Carrinho do cliente logado (null para visitante).
     */
    protected function carrinhoAtual(): ?Cart
    {
        if (! $this->clienteLogado()) {
            return null;
        }

        return Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();
    }

    public function atualizarCarrinho(): void
    {
        if (! $this->tenant || ! $this->clienteLogado()) {
            $this->totalItens = 0;
            $this->totalValor = 0;
            $this->cartItensDetalhe = [];
            return;
        }

        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        // Ignora itens cujo produto foi apagado
        $itens = $cart ? $cart->items->filter(fn($i) => $i->product) : collect();

        $precos = PrecoCliente::para(Auth::guard('cliente')->user());

        // Quantidade de produtos DIFERENTES (não a soma das unidades)
        $this->totalItens = $itens->count();
        $this->totalValor = round(
            $itens->sum(fn($i) => $precos->preco($i->product) * $i->quantity),
            2
        );

        $this->cartItensDetalhe = $itens->map(fn($item) => [
            'product_id' => $item->product_id,
            'name'       => $item->product->name,
            'category'   => $item->product->category->name ?? null,
            'price'      => $precos->preco($item->product),
            'quantity'   => $item->quantity,
            'image'      => is_array($item->product->images) ? ($item->product->images[0] ?? null) : null,
            'note'       => $item->note,
        ])->values()->toArray();
    }

    public function abrirCarrinho(): void
    {
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

        $this->carrinhoAberto = true;
    }

    public function fecharCarrinho(): void
    {
        $this->carrinhoAberto = false;
    }

    public function incrementarItem(int $productId): void
    {
        $cart = $this->carrinhoAtual();

        if (! $cart) return;

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->increment('quantity');

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function decrementarItem(int $productId): void
    {
        $cart = $this->carrinhoAtual();

        if (! $cart) return;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (! $item) return;

        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function removerItem(int $productId): void
    {
        $cart = $this->carrinhoAtual();

        if (! $cart) return;

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->delete();

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function updatedBusca(): void
    {
        $this->mostrarResultados = strlen($this->busca) >= 2;
    }

    public function fecharBusca(): void
    {
        $this->busca = '';
        $this->mostrarResultados = false;
    }

    /**
     * Encerra o login do cliente. Não usa session()->invalidate() para não
     * derrubar também o login do painel admin, caso esteja aberto no mesmo navegador.
     */
    public function sair(): void
    {
        Auth::guard('cliente')->logout();
        session()->regenerateToken();

        $this->redirect(url($this->tenant->slug), navigate: false);
    }

    public function render()
    {
        $categorias = Category::where('is_active', true)->orderBy('name')->get();
        $tenant = $this->tenant ?? app('tenant');

        $resultados = collect();

        if ($this->mostrarResultados && strlen($this->busca) >= 2 && $tenant) {
            $resultados = Product::where('is_active', true)
                ->where('name', 'like', "%{$this->busca}%")
                ->limit(8)
                ->get();
        }

        $clienteLogado = $this->clienteLogado();
        $precos = PrecoCliente::para($clienteLogado ? Auth::guard('cliente')->user() : null);

        return view('livewire.partials.navbar', compact('tenant', 'categorias', 'resultados', 'clienteLogado', 'precos'));
    }
}
