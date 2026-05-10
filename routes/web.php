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
Route::resource('plans', PlanController::class);

// New Routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/claims', function () {
    return view('claims');
})->name('claims');

Route::get('/policies', function () {
    return view('plans');
})->name('policies');

require __DIR__.'/auth.php';
