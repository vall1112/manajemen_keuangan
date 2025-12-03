<?php

use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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

// Route::get('/', function () {
//     return view('app');
// });

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api\/)[\/\w\.-]*');

Route::get('/api/transactions/{transaction}/receipt', [TransactionController::class, 'receipt']);
Route::get('/struk/{invoice}', [TransactionController::class, 'showStruk'])->name('struk.show');

Route::get('/api/master/user/{user}/card', [UserController::class, 'card'])->name('users.card');
Route::get('/card/{id}', [UserController::class, 'showCard'])->name('card.show');