<?php

use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\ProductPage;
use App\Livewire\OrderPage;
use App\Livewire\OrdersPage;
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

Route::get("/{userId}", HomePage::class);
// Route::get("/{userId}/buscar", SearchPage::class)
Route::get("/{userId}/produto/{slug}", ProductPage::class);
Route::get("/{userId}/carrinho", CartPage::class);
Route::get("/{userId}/finalizar", CheckoutPage::class);
Route::get("/{userId}/pedido/{orderId}", OrderPage::class);
Route::get("/{userId}/pedidos", OrdersPage::class);
