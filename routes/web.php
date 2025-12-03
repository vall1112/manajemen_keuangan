<?php

use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

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

// Kuitansi pembayaran tagihan
Route::get('/api/transactions/{transaction}/receipt', [TransactionController::class, 'receipt']);
Route::get('/struk/{invoice}', [TransactionController::class, 'showStruk'])->name('struk.show');

// Kartu login user
Route::get('/api/master/user/{user}/card', [UserController::class, 'card'])->name('users.card');
Route::get('/card/{id}', [UserController::class, 'showCard'])->name('card.show');

// Kartu login guru
Route::get('/api/master/teacher/{teacher}/card', [TeacherController::class, 'card'])->name('teachers.card');

// Kartu login siswa
Route::get('/api/master/student/{student}/card', [StudentController::class, 'card'])->name('students.card');
