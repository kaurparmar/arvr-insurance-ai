<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create($policyId)
    {
        $policy = Policy::with('plan')->findOrFail($policyId);

        // Ensure user owns this policy
        if ($policy->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if policy is approved
        if ($policy->status == 'pending_approval') {
            return redirect()->route('dashboard')->with('error', 'Policy must be approved before payment.');
        }

        return view('transactions.create', compact('policy'));
    }

    public function store(Request $request, $policyId)
    {
        $policy = Policy::findOrFail($policyId);

        // Ensure user owns this policy
        if ($policy->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => ['required', 'in:credit_card,debit_card,net_banking,upi'],
            'card_number' => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'string', 'size:16'],
            'expiry_month' => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'integer', 'min:1', 'max:12'],
            'expiry_year' => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'integer', 'min:' . date('Y')],
            'cvv' => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'string', 'size:3'],
            'card_holder_name' => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'string', 'max:255'],
            'upi_id' => ['required_if:payment_method,upi', 'nullable', 'string', 'max:255'],
            'bank_name' => ['required_if:payment_method,net_banking', 'nullable', 'string', 'max:255'],
        ]);

        // Simulate payment processing
        $paymentSuccessful = $this->processPayment($request->all());

        if ($paymentSuccessful) {
            // Create transaction record
            $transaction = Transaction::create([
                'policy_id' => $policy->_id,
                'user_id' => auth()->id(),
                'amount' => $policy->premium_paid,
                'payment_method' => $request->payment_method,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'completed',
                'payment_date' => now(),
                'details' => [
                    'card_last_four' => $request->card_number ? substr($request->card_number, -4) : null,
                    'upi_id' => $request->upi_id,
                    'bank_name' => $request->bank_name,
                ],
            ]);

            // Update policy status to active
            $policy->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ]);

            return redirect()->route('transactions.success', $transaction->_id ?? $transaction->id);
        } else {
            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function success($transactionId)
    {
        $transaction = Transaction::with(['policy.plan', 'user'])->findOrFail($transactionId);

        // Ensure user owns this transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transactions.success', compact('transaction'));
    }

    private function processPayment($paymentData)
    {
        // Simulate payment processing delay
        sleep(2);

        // Simulate 95% success rate
        return rand(1, 100) <= 95;
    }
}