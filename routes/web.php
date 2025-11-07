<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login' , 'auth.login');
Route::post('/login' , [LoginController::class , 'login'])->name('login.user');

Route::view('/email-verification' , 'auth.email-verification')->name('email.verification');

Route::view('/register' , 'auth.register')->name('register.user');

Route::view('/forgot-password' , 'auth.forgot-password');
Route::post('/forgot-password' , [LoginController::class , 'forgotPassword'])->name('forgot.password');

Route::get('/verify-email/{id}', [LoginController::class, 'verify'])->name('verify.email');
Route::get('/email-verify', [LoginController::class, 'emailVerify'])->name('email.verify');


Route::view('/reset-password/{user}' , 'auth.reset-password')->name('reset.password.view');
Route::post('/reset-password/{user}' , [LoginController::class , 'resetPassword'])->name('reset.password');

Route::middleware(['emailVerification' , 'authLogin'])->group(function (){
    Route::resource('user' , UserController::class);
    Route::resource('role' , RoleController::class);
    Route::resource('chat' , ChatController::class);
    Route::resource('product' , ProductController::class);
    Route::resource('permission' , PermissionController::class);
    Route::resource('order' , OrderController::class);

    Route::get('/credit/add' , [UserController::class , 'creditAdd'])->name('credit.add');
    Route::get('/delete-variant' , [ProductController::class , 'deleteVariant'])->name('delete.variant');
    Route::get('/menu' , [MenuController::class , 'MenuView'])->name('menu.view');
    Route::get('/cart' , [MenuController::class , 'addToCart'])->name('add.cart');
    Route::get('/cart/check' , [MenuController::class , 'CartCheck'])->name('cart.check');
    Route::get('/update-quantity' , [MenuController::class , 'updateQuantity'])->name('update.quantity');
    Route::get('/close/cart' , [MenuController::class , 'cartItemClose'])->name('delete.cart');
    Route::get('/checkout/show' , [CheckoutController::class ,  'checkoutShow'])->name('checkout.show');
    Route::get('/checkout/stripe/payment' , [CheckoutController::class ,  'cashierPaymentIntent'])->name('cashier.payment.intent');
    Route::post('/checkout/form/data' , [CheckoutController::class ,  'checkoutFormData'])->name('checkout.form.data');
    Route::post('/checkout/order/create' , [CheckoutController::class ,  'orderCreate'])->name('checkout.order.create');
    Route::get('/payment/success' , [OrderController::class ,  'paymentSuccess'])->name('payment.success');
    Route::get('/search/product/items' , [OrderController::class ,  'searchProductItems'])->name('search.order.items');
    Route::put('order/edit/details/{id}', [OrderController::class, 'orderDetailsEdit'])->name('order.update.details');
    Route::get('select/single/product', [OrderController::class, 'selectSingleProduct'])->name('select.single.product');
    Route::get('/message-store' , [ChatController::class , 'messageStore'])->name('message.store');
    Route::get('/message-get' , [ChatController::class , 'allMessageGet'])->name('chat.get.messages');
    Route::get('/search-user' , [ChatController::class , 'SearchUser'])->name('search.user');
    Route::get('/logout' , [LoginController::class , 'logout'])->name('logout');
});
