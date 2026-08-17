<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CheckoutOptionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductOfferController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SupportMessageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:6,1');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
        Route::get('/produtos/novo', [ProductController::class, 'create'])->name('products.create');
        Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/produtos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/produtos/{product}/duplicar', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::post('/produtos/{product}/ativar', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
        Route::delete('/produtos/{product}/imagens/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::post('/produtos/{product}/ofertas', [ProductOfferController::class, 'store'])->name('products.offers.store');
        Route::put('/produtos/{product}/ofertas/{offer}', [ProductOfferController::class, 'update'])->name('products.offers.update');
        Route::delete('/produtos/{product}/ofertas/{offer}', [ProductOfferController::class, 'destroy'])->name('products.offers.destroy');

        Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/ingredientes', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::post('/ingredientes', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::put('/ingredientes/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
        Route::delete('/ingredientes/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

        Route::get('/entrega-pagamento', [CheckoutOptionController::class, 'index'])->name('checkout-options.index');
        Route::post('/entrega-pagamento/entrega', [CheckoutOptionController::class, 'storeShipping'])->name('checkout-options.shipping.store');
        Route::put('/entrega-pagamento/entrega/{shippingMethod}', [CheckoutOptionController::class, 'updateShipping'])->name('checkout-options.shipping.update');
        Route::delete('/entrega-pagamento/entrega/{shippingMethod}', [CheckoutOptionController::class, 'destroyShipping'])->name('checkout-options.shipping.destroy');
        Route::post('/entrega-pagamento/pagamento', [CheckoutOptionController::class, 'storePayment'])->name('checkout-options.payment.store');
        Route::put('/entrega-pagamento/pagamento/{paymentMethod}', [CheckoutOptionController::class, 'updatePayment'])->name('checkout-options.payment.update');
        Route::delete('/entrega-pagamento/pagamento/{paymentMethod}', [CheckoutOptionController::class, 'destroyPayment'])->name('checkout-options.payment.destroy');

        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/{product}', [StockController::class, 'movements'])->name('stock.movements');
        Route::post('/stock/{product}', [StockController::class, 'store'])->name('stock.store');

        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/pedidos/{order:order_number}/estado', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/clientes', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/clientes/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        Route::get('/avaliacoes', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/avaliacoes/{review}/estado', [ReviewController::class, 'updateStatus'])->name('reviews.status');

        Route::get('/cupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/cupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::put('/cupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/cupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
        Route::delete('/newsletter/{subscriber}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

        Route::get('/mensagens', [SupportMessageController::class, 'index'])->name('messages.index');
        Route::post('/mensagens/{message}/estado', [SupportMessageController::class, 'updateStatus'])->name('messages.status');

        Route::get('/faq', [FaqController::class, 'index'])->name('faqs.index');
        Route::post('/faq', [FaqController::class, 'store'])->name('faqs.store');
        Route::put('/faq/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('/faq/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

        Route::get('/depoimentos', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::post('/depoimentos', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::delete('/depoimentos/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

        Route::get('/utilizadores', [UserController::class, 'index'])->name('users.index');
        Route::post('/utilizadores', [UserController::class, 'store'])->name('users.store');
        Route::delete('/utilizadores/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/contactos', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/contactos', [SiteSettingController::class, 'update'])->name('settings.update');
    });
});
