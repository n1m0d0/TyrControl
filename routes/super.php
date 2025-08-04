<?php

use Illuminate\Support\Facades\Route;

Route::view('supplier', 'pages.supplier')->name('supplier');
Route::view('batch', 'pages.batch')->name('batch');
Route::view('inventory', 'pages.inventory')->name('inventory');
Route::view('movement', 'pages.movement')->name('movement');
