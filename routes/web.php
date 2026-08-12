<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/loja', [ShopController::class, 'index'])->name('shop.index');

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
