<?php

use Illuminate\Support\Facades\Route;

Route::view('client', 'pages.client')->name('client');
Route::view('box-session', 'pages.box-session')->name('box-session');
Route::view('sale', 'pages.sale')->name('sale');
Route::view('detail/{id}', 'pages.detail')->name('detail');
