<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanetController;

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
    return view('default');
})->name('home');

Route::get('/planets', [PlanetController::class, 'index'])->name('planets');
Route::post('/sync-data', [PlanetController::class, 'triggerSyncCommand'])->name('trigger.command');
