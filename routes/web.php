<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('Auth.login');
});

Route::get('/beranda', function () {
    return view('beranda');
})->name('beranda');

Route::get('/kategori', function () {
    return view('kategori');
})->name('kategori');
