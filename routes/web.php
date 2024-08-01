<?php

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
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\Holiday1Controller;

Route::get('/fetch-holidays1', [Holiday1Controller::class, 'fetchHolidays'])->name('holidays.fetch');
Route::get('/holidays1', [Holiday1Controller::class, 'index'])->name('holidays.index1');
Route::get('/fetch-holidays', [HolidayController::class, 'fetchHolidays'])->name('holidays.fetch');
Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
Route::get('/', function () {
    return view('welcome');
});
