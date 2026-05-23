<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PolicyApplicationController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AIInsuranceController;
use App\Http\Controllers\Admin\ClaimResolutionController;
use Illuminate\Support\Facades\Route;

// --- Public Guest Workspace Routes ---
Route::get('/', [PageController::class, 'welcome'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/vr', fn () => view('vr'))->name('vr');
Route::get('/calculator', fn () => view('calculator'))->name('calculator');
Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');

Route::get('/lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
});

Route::resource('plans', PlanController::class)->only(['index', 'show']);

// --- Secure Authenticated User Workspace Group ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Core AI Multi-Agent Nexus Endpoints
    Route::get('/ai-nexus', [AIInsuranceController::class, 'index'])->name('ai.nexus');
    Route::post('/ai-nexus/chat/{agent}', [AIInsuranceController::class, 'chat'])->name('ai.nexus.chat');

    // 2. Client Space Dashboard
    Route::get('/dashboard', function () {
        $policies = auth()->user()->policies()->with('plan')->get();
        $activePoliciesCount = $policies->where('status', 'active')->count();
        $coverageTotal = $policies->sum(fn ($policy) => $policy->plan?->coverage_amount ?? 0);
        $monthlyPremiumTotal = $policies->sum('premium_paid');

        return view('dashboard', compact('policies', 'activePoliciesCount', 'coverageTotal', 'monthlyPremiumTotal'));
    })->name('dashboard');

    // 3. Document Application Management
    Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');
    Route::get('/policies/apply/{plan}', [PolicyApplicationController::class, 'create'])->name('policies.apply');
    Route::post('/policies/apply/{plan}', [PolicyApplicationController::class, 'store'])->name('policies.apply.store');
    Route::get('/policies/application/{policy}/success', [PolicyApplicationController::class, 'success'])->name('policies.application.success');
    Route::post('/purchase', [PolicyController::class, 'store'])->name('purchase.store');

    // 4. Financial Ledger Processing
    Route::get('/transactions/{policy}/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/{policy}', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/success', [TransactionController::class, 'success'])->name('transactions.success');

    // 5. Claims Processing Engine
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims');
    Route::get('/claims/create', [ClaimController::class, 'create'])->name('claims.create');
    Route::get('/claims/{id}', [ClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');

    // 6. User Profiles Security
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Secure Administrative Supervisor Workspace Group ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Audit Dashboard Matrix View
    Route::get('/claims', [ClaimResolutionController::class, 'index'])->name('claims.index');
    // Decision Processor Form Pipeline (Approve / Reject UI actions)
    Route::post('/claims/{id}/resolve', [ClaimResolutionController::class, 'resolve'])->name('claims.resolve');
});

require __DIR__.'/auth.php';