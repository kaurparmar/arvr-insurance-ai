<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'welcome'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/vr', function () {
    return view('vr');
})->name('vr');
Route::get('/calculator', function () {
    return view('calculator');
})->name('calculator');
Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $policies = auth()->user()->policies()->with('plan')->get();
        $activePoliciesCount = $policies->where('status', 'active')->count();
        $coverageTotal = $policies->sum(fn ($policy) => $policy->plan?->coverage_amount ?? 0);
        $monthlyPremiumTotal = $policies->sum('premium_paid');

        return view('dashboard', compact('policies', 'activePoliciesCount', 'coverageTotal', 'monthlyPremiumTotal'));
    })->name('dashboard');

    Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::post('/purchase', [PolicyController::class, 'store'])->name('purchase.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('plans', PlanController::class)->only(['index', 'show']);

require __DIR__.'/auth.php';
