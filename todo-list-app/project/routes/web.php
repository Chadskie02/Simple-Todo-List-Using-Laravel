<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('todos', [TodoController::class, 'index']);
Route::post('todos', [TodoController::class, 'store']);
Route::get('fetch-todos', [TodoController::class, 'fetchtodo']);
Route::get('edit-todo/{id}', [TodoController::class, 'edit']);
Route::put('update-todo/{id}', [TodoController::class, 'update']);
Route::delete('delete-todo/{id}', [TodoController::class, 'destroy']);
