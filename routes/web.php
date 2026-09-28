<?php

use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\ProductPage;
use App\Livewire\OrderPage;
use App\Livewire\OrdersPage;
use App\Livewire\CategoryPage;
use App\Livewire\OfertasPage;
use Illuminate\Support\Facades\Route;

// Públicas: visitante vê só imagem e nome (preço escondido no componente)
Route::get("/{slug}", HomePage::class);
Route::get('{slug}/ofertas', OfertasPage::class);
Route::get('{slug}/categoria/{categoriaSlug}', CategoryPage::class);

// Exigem cliente logado nesta loja
Route::middleware('cliente.auth')->group(function () {
    Route::get("/{slug}/produto/{produto_slug}", ProductPage::class);
    Route::get('{slug}/finalizar', CheckoutPage::class);
    Route::get("/{slug}/pedido/{orderId}", OrderPage::class);
    Route::get("/{slug}/pedidos", OrdersPage::class);
});
