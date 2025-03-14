<?php

use App\Http\Controllers\RssFeedController;
use App\Http\Middleware\ValidateSectionMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sections/{section}', [RssFeedController::class, 'getFeed'])
    ->middleware(ValidateSectionMiddleware::class);
