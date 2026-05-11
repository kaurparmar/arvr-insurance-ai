<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyApplicationController extends Controller
{
    public function create($planId)
    {
        $plan = Plan::findOrFail($planId);
        return view('policies.apply', compact('plan'));
    }

    public function store(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'occupation' => ['required', 'string', 'max:100'],
            'annual_income' => ['required', 'numeric', 'min:100000'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],

            // Documents
            'photo_id' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'address_proof' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'income_proof' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'medical_certificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'other_documents.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ]);

        // Handle file uploads
        $documents = [];

        $documentFields = [
            'photo_id' => 'photo-ids',
            'address_proof' => 'address-proofs',
            'income_proof' => 'income-proofs',
            'medical_certificate' => 'medical-certificates',
        ];

        foreach ($documentFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $documents[$field] = $request->file($field)->store("applications/{$folder}", 'public');
            }
        }

        if ($request->hasFile('other_documents')) {
            $documents['other_documents'] = [];
            foreach ($request->file('other_documents') as $file) {
                $documents['other_documents'][] = $file->store('applications/other-documents', 'public');
            }
        }

        // Create policy application
        $policy = Policy::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->_id,
            'policy_number' => 'LSXR-' . strtoupper(uniqid()),
            'status' => 'pending_approval',
            'premium_paid' => $plan->premium_amount,
            'coverage_amount' => $plan->coverage_amount,
            'start_date' => now(),
            'end_date' => now()->addYear(),

            // Personal Information
            'applicant_details' => [
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'annual_income' => $request->annual_income,
                'emergency_contact' => [
                    'name' => $request->emergency_contact_name,
                    'phone' => $request->emergency_contact_phone,
                    'relationship' => $request->emergency_contact_relationship,
                ],
            ],

            // Documents
            'documents' => $documents,
            'application_date' => now(),
        ]);

        return redirect()->route('policies.application.success', $policy->_id ?? $policy->id)
            ->with('success', 'Policy application submitted successfully! We will review your documents and get back to you within 24-48 hours.');
    }

    public function success($policyId)
    {
        $policy = Policy::with('plan')->findOrFail($policyId);

        // Ensure user owns this policy
        if ($policy->user_id !== auth()->id()) {
            abort(403);
        }

        return view('policies.success', compact('policy'));
    }
}