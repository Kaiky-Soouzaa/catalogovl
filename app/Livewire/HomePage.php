<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Banner;
use App\Services\PrecoCliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HomePage extends Component
{
    public User $tenant;
    public string $busca = '';
    public int $totalItens = 0;
    public float $totalValor = 0;
    public array $adicionados = [];
    public array $quantidades = [];

    protected $listeners = ['cartUpdated' => 'atualizarCarrinho'];

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);
        $this->atualizarCarrinho();
    }

    /**
     * Cliente logado E pertencente a esta loja.
     */
    protected function clienteLogado(): bool
    {
        $cliente = Auth::guard('cliente')->user();

        return $cliente !== null
            && (int) $cliente->tenant_id === (int) $this->tenant->id;
    }

    public function atualizarCarrinho(): void
    {
        if (! $this->clienteLogado()) {
            $this->totalItens = 0;
            $this->totalValor = 0;
            $this->adicionados = [];
            $this->quantidades = [];
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

        $this->adicionados = $itens->pluck('product_id')->values()->toArray();
        $this->quantidades = $itens->pluck('quantity', 'product_id')->toArray();
    }

    public function adicionarRapido(int $productId): void
    {
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

        $sessionId = session()->getId();

        $cart = Cart::firstOrCreate([
            'user_id'    => $this->tenant->id,
            'session_id' => $sessionId,
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $productId,
                'quantity'   => 1,
                'note'       => '',
            ]);
        }

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function incrementarHome(int $productId): void
    {
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) $item->increment('quantity');

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function decrementarHome(int $productId): void
    {
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$item) return;

        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $clienteLogado = $this->clienteLogado();
        $precos = PrecoCliente::para($clienteLogado ? Auth::guard('cliente')->user() : null);
        $idsNegociados = $precos->idsNegociadosComDesconto();

        $categorias = Category::with(['products' => function ($query) {
            $query->where('is_active', true)->orderBy('name');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Ofertas: as marcadas como on_sale para todos + as negociadas com desconto para este cliente.
        // Para o cliente logado, o filtro final confere a regra dele (emOferta).
        $produtosOferta = Product::where('is_active', true)
            ->where(function ($q) use ($idsNegociados) {
                $q->where(function ($g) {
                    $g->where('on_sale', true)->whereNotNull('original_price');
                })->orWhereIn('id', $idsNegociados);
            })
            ->when($this->busca, fn($q) => $q->where('name', 'like', "%{$this->busca}%"))
            ->orderBy('name')
            ->get()
            ->when($clienteLogado, fn($c) => $c->filter(fn($p) => $precos->emOferta($p))->values());

        if ($this->busca) {
            $categorias = Category::with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('name', 'like', "%{$this->busca}%")
                    ->orderBy('name');
            }])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $banners = Banner::where('user_id', $this->tenant->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('livewire.home-page', compact('categorias', 'produtosOferta', 'banners', 'clienteLogado', 'precos'))
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
