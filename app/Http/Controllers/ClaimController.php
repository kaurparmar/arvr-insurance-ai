<?php

namespace App\Http\Controllers;
use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    //
    public function store(Request $request)
    {
        Claim::create([
    'policy_id' => $request->policy_id,
    'user_id' => auth()->id(),
    'claim_reason' => $request->claim_reason,
    'documents' => [],
    'claim_amount' => $request->claim_amount,
    'status' => 'pending',
    'submitted_at' => now(),
]);

        return redirect('/dashboard')->with('success', 'Policy Purchased!');
    }
}
