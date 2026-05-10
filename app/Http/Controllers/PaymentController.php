<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
class PaymentController extends Controller
{
    //
    public function store(Request $request)
    {
        Payment::create([
    'user_id' => auth()->id(),
    'policy_id' => $request->policy_id,
    'amount' => 15000,
    'payment_method' => 'UPI',
    'transaction_id' => uniqid('TXN'),
    'status' => 'success',
    'paid_at' => now(),
]); 

        return redirect('/dashboard')->with('success', 'Policy Purchased!');
    }
}
