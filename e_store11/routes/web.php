<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // include 'route.php';
    return view('welcome');
});
