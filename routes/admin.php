<?php

use Illuminate\Support\Facades\Route;

Route::view('user', 'pages.user')->name('user');
Route::view('company', 'pages.company')->name('company');
Route::view('branch', 'pages.branch')->name('branch');
Route::view('box', 'pages.box')->name('box');
Route::view('warehouse', 'pages.warehouse')->name('warehouse');
Route::view('brand', 'pages.brand')->name('brand');
Route::view('category', 'pages.category')->name('category');
Route::view('product', 'pages.product')->name('product');
