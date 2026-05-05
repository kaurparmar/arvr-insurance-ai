<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\PolicyController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{id}', [PlanController::class, 'show']);

Route::get('/calculator', function () {
    return view('calculator');
});
Route::post('/calculate', [CalculatorController::class, 'calculate']);
Route::get('/vr',function(){
    return view('vr');
})->name('vr');
Route::post('/purchase', [PolicyController::class, 'store']);
require __DIR__.'/auth.php';
