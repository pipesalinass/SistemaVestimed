<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccesoAdmin\HomeController;
use App\Http\Controllers\AccesoAdmin\RoleController;
use App\Http\Controllers\AccesoAdmin\UserController;


Route::get('', [HomeController::class, 'index'])->name('home');

Route::resource('roles', RoleController::class)->names('roles');

Route::resource('users', UserController::class)->names('users');
