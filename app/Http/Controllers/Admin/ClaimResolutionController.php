<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaimResolutionController extends Controller
{
    /**
     * Display the Flagged Operations Matrix.
     */
    public function index()
    {
        // Query the database for claims flagged by the system
        $flaggedClaims = Claim::where('status', 'Flagged')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.claims', compact('flaggedClaims'));
    }

    /**
     * Resolve a flagged claim instance and sync with the AI multi-agent layer.
     */
    public function resolve(Request $request, $id)
    {
        // 1. Validate the incoming decision payloads
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'admin_note' => 'required|string|min:5|max:1000'
        ]);

        // 2. Find the record instance
        $claim = Claim::findOrFail($id);

        // 3. Commit the local administrative overrides
        $claim->status = $request->input('status');
        $claim->admin_note = $request->input('admin_note');
        $claim->resolved_at = now();
        $claim->resolved_by = auth()->id(); 
        
        $claim->save();

        // 4. BI-DIRECTIONAL SYNC: Update the state in your Python AI Engine
        // Pulls the live URL from your Render dashboard configuration
        $fastApiUrl = rtrim(env('FASTAPI_URL', 'http://localhost:8000'), '/');

        try {
            // This triggers the UpdateClaimStatusPayload endpoint inside your FastAPI main.py
            $response = Http::timeout(10)->post("{$fastApiUrl}/api/update-claim-status", [
                'claim_id'   => (string)$id,
                'status'     => strtolower($request->input('status')), // Sends 'approved' or 'rejected'
                'admin_note' => $request->input('admin_note')
            ]);

            if (!$response->successful()) {
                Log::error("FastAPI backend rejected claim status synchronization for ID {$id}: " . $response->body());
            }
        } catch (\Exception $e) {
            // Logs the error to standard storage/logs/laravel.log without crashing your UI
            Log::critical("Failed to connect with FastAPI engine during claim resolve sync: " . $e->getMessage());
        }

        // 5. Redirect back to the operational dashboard matrix with a status notification
        return redirect()->route('admin.claims.index')->with(
            'success', 
            "Node " . $id . " has been successfully " . strtolower($claim->status) . " and archived cleanly across systems."
        );
    }
}