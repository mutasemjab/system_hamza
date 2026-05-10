<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\FrontCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FrontProductController;
use App\Http\Controllers\FrontOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FrontCurrencyController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserAddressController;
use Illuminate\Support\Facades\Route;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichf
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



    Route::get('/', [HomeController::class, 'index'])->name('home');
});
