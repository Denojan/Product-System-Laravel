<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductTypeController;
Route::get('/', function () {
    return view('auth/login');
});


Route::controller(AuthController::class)->group(function (){
    Route::get('register','register')->name('register');
    Route::post('register','registerSave')->name('register.save');

    Route::get('login','login')->name('login');
    Route::post('login','loginAction')->name('login.action');

    Route::get('logout','logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function (){
    Route::get('dashboard',function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(OrderController::class)->prefix('orders')->group(function (){
        Route::get('','index')->name('orders');
        Route::get('create','create')->name('orders.create');
        Route::post('store/{id}','store')->name('orders.store');
        Route::get('show/{id}','show')->name('orders.show');
        Route::get('edit/{id}','edit')->name('orders.edit');
        Route::put('edit/{id}','update')->name('orders.update');
        Route::delete('destroy/{id}','destroy')->name('orders.destroy');
        Route::post('accept/{id}','accept')->name('orders.accept');
    });
    
    Route::get('/profile',[App\Http\Controllers\AuthController::class,'profile'])->name('profile');
    Route::post('/editprofile/{id}',[App\Http\Controllers\AuthController::class,'editprofile'])->name('editprofile');


    Route::controller(ProductTypeController::class)->prefix('productstype')->group(function (){
        Route::get('','index')->name('productstype');
        Route::get('createtype','create')->name('productstype.create');
        Route::post('storetype/{id}','store')->name('productstype.store');
        Route::get('showtype/{id}','show')->name('productstype.show');
        Route::get('order/{id}','details')->name('productstype.details');
        Route::get('edittype/{id}','edit')->name('productstype.edit');
        Route::put('edittype/{id}','update')->name('productstype.update');
        Route::delete('destroytype/{id}','destroy')->name('productstype.destroy');
      
    });
});