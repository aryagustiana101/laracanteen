<?php

use App\Imports\ExcelUtils;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthenticationController;
use App\Utilities\Constant;

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

    return Constant::$incomingStatus;

    // $Import = new StudentsImport();
    // $ts = \Maatwebsite\Excel\Facades\Excel::import($Import, 'users.xlsx');
    // return (new StudentsImport)->toArray('students.xlsx');
    // return $Import->getSheetNames();

    // $Import = new ExcelUtils();
    // $ts = \Maatwebsite\Excel\Facades\Excel::import($Import, 'students.xlsx');
    // return $Import->getSheetNames();

    // return (new StudentsImport)->toArray('students.xlsx');
    // return (new StudentsImport)->toCollection('students.xlsx');
    return response()->json('Laravel version ' . Illuminate\Foundation\Application::VERSION);
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'authenticate']);
});


Route::group(['middleware' => ['auth', 'revalidate']], function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PageController::class, 'profile']);

    Route::get('/withdraw', [WithdrawController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/incomes', [IncomeController::class, 'index']);
    Route::get('/transactions', [TransactionController::class, 'index']);

    Route::post('/logout', [AuthenticationController::class, 'logout']);
});
