<?php

use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\DeliveryPage;
use App\Livewire\HomePage;
use App\Livewire\ProductPage;
use App\Livewire\OrderPage;
use App\Livewire\OrdersPage;
use App\Livewire\PaymentMethodPage;
use App\Livewire\PaymentTypePage;
use App\Livewire\SearchPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/{slug}", HomePage::class);
Route::get("/{slug}/produto/{produto_slug}", ProductPage::class);
Route::get("/{slug}/carrinho", CartPage::class);
Route::get("/{slug}/finalizar", DeliveryPage::class);
Route::get("/{slug}/finalizar/pagamento", PaymentTypePage::class);
Route::get("/{slug}/finalizar/metodo", PaymentMethodPage::class);
Route::get("/{slug}/finalizar/dados", CheckoutPage::class);
Route::get("/{slug}/pedido/{orderId}", OrderPage::class);
Route::get("/{slug}/pedidos", OrdersPage::class);
