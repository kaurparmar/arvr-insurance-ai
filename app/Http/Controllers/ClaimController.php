<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::with(['policy.plan'])
            ->where('user_id', auth()->id())
            ->get();

        $policies = auth()->user()->policies()->with('plan')->get();

        return view('claims', compact('claims', 'policies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'claim_reason' => ['required', 'string', 'max:1200'],
            'claim_amount' => ['required', 'numeric'],
        ]);

        Claim::create([
            'policy_id' => $request->policy_id,
            'user_id' => auth()->id(),
            'claim_reason' => $request->claim_reason,
            'documents' => [],
            'claim_amount' => $request->claim_amount,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('claims')->with('success', 'Claim submitted successfully.');
    }
}
