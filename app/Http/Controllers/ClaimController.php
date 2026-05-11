<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::with(['policy.plan'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $policies = auth()->user()->policies()->with('plan')->get();

        return view('claims', compact('claims', 'policies'));
    }

    public function create()
    {
        $policies = auth()->user()->policies()->with('plan')->get();
        return view('claims.create', compact('policies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'policy_id' => ['required', 'exists:policies,_id'],
            'claim_reason' => ['required', 'string', 'max:2000'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_location' => ['required', 'string', 'max:255'],
            'claim_amount' => ['required', 'numeric', 'min:100', 'max:10000000'],
            'witnesses' => ['nullable', 'string', 'max:1000'],
            'medical_reports.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'police_report' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'damage_photos.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'other_documents.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ]);

        $medicalReports = [];
        $damagePhotos = [];
        $otherDocuments = [];

        // Handle file uploads
        if ($request->hasFile('medical_reports')) {
            foreach ($request->file('medical_reports') as $file) {
                $path = $file->store('claims/medical-reports', 'public');
                $medicalReports[] = $path;
            }
        }

        if ($request->hasFile('damage_photos')) {
            foreach ($request->file('damage_photos') as $file) {
                $path = $file->store('claims/damage-photos', 'public');
                $damagePhotos[] = $path;
            }
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $path = $file->store('claims/other-documents', 'public');
                $otherDocuments[] = $path;
            }
        }

        $policeReport = null;
        if ($request->hasFile('police_report')) {
            $policeReport = $request->file('police_report')->store('claims/police-reports', 'public');
        }

        Claim::create([
            'policy_id' => $request->policy_id,
            'user_id' => auth()->id(),
            'claim_reason' => $request->claim_reason,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'witnesses' => $request->witnesses,
            'medical_reports' => $medicalReports,
            'police_report' => $policeReport,
            'damage_photos' => $damagePhotos,
            'other_documents' => $otherDocuments,
            'claim_amount' => $request->claim_amount,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('claims')->with('success', 'Claim submitted successfully. You will receive updates via email.');
    }

    public function show($id)
    {
        $claim = Claim::with(['policy.plan', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('claims.show', compact('claim'));
    }
}
