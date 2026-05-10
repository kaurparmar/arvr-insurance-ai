<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function store(Request $request)
    {
        Policy::create([
    'user_id' => auth()->id(),
    'plan_id' => $request->plan_id,
    'policy_number' => 'POL' . rand(10000,99999),
    'start_date' => now(),
    'end_date' => now()->addYear(),
    'premium_paid' => 15000,
    'payment_status' => 'paid',
    'status' => 'active',
]);

        return redirect('/dashboard')->with('success', 'Policy Purchased!');
    }
}