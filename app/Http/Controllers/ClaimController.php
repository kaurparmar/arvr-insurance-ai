<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    /**
     * Display a listing of the user's claims alongside their insurance policies.
     */
    public function index()
    {
        $claims = Claim::with(['policy.plan'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $policies = auth()->user()->policies()->with('plan')->get();

        return view('claims', compact('claims', 'policies'));
    }

    /**
     * Show the form for creating a new claim entry.
     */
    public function create()
    {
        $policies = auth()->user()->policies()->with('plan')->get();
        return view('claims.create', compact('policies'));
    }

    /**
     * Store a newly created claim, process attachments, and trigger the automated AI agent pipeline.
     */
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

        // Retain original multi-file storage arrays
        if ($request->hasFile('medical_reports')) {
            foreach ($request->file('medical_reports') as $file) {
                $medicalReports[] = $file->store('claims/medical-reports', 'public');
            }
        }

        if ($request->hasFile('damage_photos')) {
            foreach ($request->file('damage_photos') as $file) {
                $damagePhotos[] = $file->store('claims/damage-photos', 'public');
            }
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $otherDocuments[] = $file->store('claims/other-documents', 'public');
            }
        }

        $policeReport = null;
        if ($request->hasFile('police_report')) {
            $policeReport = $request->file('police_report')->store('claims/police-reports', 'public');
        }

        // 1. Persist the complete record using Eloquent Model definitions
        $claim = Claim::create([
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
            'claim_amount' => (float)$request->claim_amount,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // 2. Dispatch payload string asynchronously over the internal network to the AI Orchestrator
        try {
            // Falls back securely to the internal monolith port 8001 if config mapping is missing
            $baseUrl = config('services.ai_backend.url', 'http://127.0.0.1:8001');
            $apiUrl = $baseUrl . '/api/evaluate-claim';

            $response = Http::timeout(12)->post($apiUrl, [
                'claim_id' => (string)$claim->_id
            ]);

            if ($response->successful()) {
                $evalData = $response->json();
                
                return redirect()->route('claims')->with('flash_ai', [
                    'recommendation' => $evalData['recommendation'] ?? 'Under Review',
                    'reason' => $evalData['reason'] ?? 'Dispatched to validation pipeline successfully.'
                ]);
            }
        } catch (\Exception $e) {
            // Fail gracefully without interrupting user flow if AI background service is waking up
        }

        return redirect()->route('claims')->with('success', 'Claim submitted successfully. You will receive updates via email.');
    }

    /**
     * Show an individual claim detail profile.
     */
    public function show($id)
    {
        $claim = Claim::with(['policy.plan', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('claims.show', compact('claim'));
    }

    /**
     * Administrative Dashboard listing matching flagged AI risk reviews.
     */
    public function adminIndex()
    {
        // Retains full access tracking query for the admin panel review queue
        $flaggedClaims = Claim::with(['policy.plan', 'user'])
            ->where('status', 'Flagged For Review')
            ->orWhere('ai_recommendation', 'flag')
            ->latest()
            ->get();

        return view('admin.claims', compact('flaggedClaims'));
    }

    /**
     * Admin action override method syncing human manual decision changes with the AI logic pipeline.
     */
    public function adminResolve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'admin_note' => 'required|string'
        ]);

        try {
            $baseUrl = config('services.ai_backend.url', 'http://127.0.0.1:8001');
            $apiUrl = $baseUrl . '/api/update-claim-status';

            $response = Http::post($apiUrl, [
                'claim_id' => $id,
                'status' => $request->status,
                'admin_note' => $request->admin_note
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Claim ledger status resolved successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to synchronize decision with analytics pipeline.');
        }

        return redirect()->back()->with('error', 'System connection failed during synchronization.');
    }
}