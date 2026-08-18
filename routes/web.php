<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AjudaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SupportMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/loja', [ShopController::class, 'index'])->name('shop.index');

Route::get('/produto/{product:slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/nossa-historia', [HistoriaController::class, 'index'])->name('historia.index');

Route::get('/centro-de-ajuda', [AjudaController::class, 'index'])->name('ajuda.index');
Route::post('/centro-de-ajuda/mensagem', [SupportMessageController::class, 'store'])->name('support-message.store')->middleware('throttle:10,1');

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:10,1');

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/adicionar-varios', [CartController::class, 'addMany'])->name('cart.add.many');
Route::post('/carrinho/cupom', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply')->middleware('throttle:15,1');
Route::delete('/carrinho/cupom', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::patch('/carrinho/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');

Route::get('/pedido/{order:order_number}', [OrderController::class, 'show'])->name('order.show');

Route::get('/clientes-satisfeitos', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/clientes-satisfeitos/avaliar', [ReviewController::class, 'store'])->name('reviews.store')->middleware('throttle:5,1');

Route::get('/quiz-da-pele', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz-da-pele/resultado', [QuizController::class, 'result'])->name('quiz.result');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:6,1');
    Route::get('/registo', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registo', [AuthController::class, 'register'])->name('register.store')->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/minha-conta', [AccountController::class, 'index'])->name('account.index');
    Route::post('/minha-conta/perfil', [AccountController::class, 'updateProfile'])->name('account.profile.update');

    Route::get('/minha-conta/pedidos', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/minha-conta/pedidos/{order:order_number}', [AccountController::class, 'orderShow'])->name('account.orders.show');

    Route::get('/minha-conta/enderecos', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/minha-conta/enderecos', [AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::delete('/minha-conta/enderecos/{address}', [AccountController::class, 'destroyAddress'])->name('account.addresses.destroy');

    Route::get('/minha-conta/favoritos', [AccountController::class, 'favorites'])->name('account.favorites');
    Route::get('/minha-conta/avaliacoes', [AccountController::class, 'reviews'])->name('account.reviews');

    Route::post('/favoritos/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favoritos/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

require __DIR__ . '/admin.php';
