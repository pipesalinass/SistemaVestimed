<?php

use App\Http\Controllers\PDFController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/inicio', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/pedido', function () {
    return view('pedido');
})->name('pedido');

Route::middleware(['auth:sanctum', 'verified'])->get('/post-pedido', function () {
    return view('postPedido');
})->name('postPedido');

Route::middleware(['auth:sanctum', 'verified'])->get('/modelo', function () {
    return view('modelo');
})->name('modelo');

Route::middleware(['auth:sanctum', 'verified'])->get('/generatePDF/{id}', [PDFController::class, 'generatePDF']);
Route::middleware(['auth:sanctum', 'verified'])->get('/generateNombres/{id}', [PDFController::class, 'generateNombres']);

Route::get('registrar',[RegisterController::class, 'create'])->name('registrar');
Route::post('registrar',[RegisterController::class, 'store'])->name('registrar.store');
