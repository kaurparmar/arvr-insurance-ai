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
            'start_date' => now(),
        ]);

        return redirect('/dashboard')->with('success', 'Policy Purchased!');
    }
}