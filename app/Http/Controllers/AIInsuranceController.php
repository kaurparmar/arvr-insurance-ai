<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIInsuranceController extends Controller
{
    /**
     * Pass the agent configurations straight to the UI view
     */
    public function index()
    {
        $agents = config('nexus.agents');
        return view('ai-nexus', compact('agents'));
    }

    /**
     * Routes and handles the multi-agent orchestrator requests
     */
    public function chat(Request $request, $agentKey)
    {
        $agents = config('nexus.agents');

        // Fail-safe fallback if an unrecognized agent key is intercepted
        if (!array_key_exists($agentKey, $agents)) {
            return response()->json(['reply' => "Agent configuration node standard [{$agentKey}] not found in core registry."], 404);
        }

        $selectedAgent = $agents[$agentKey];
        $request->validate(['message' => 'required|string']);

        // Pull the live FastAPI URL configured on your Render dashboard environment settings
        $fastApiUrl = rtrim(env('FASTAPI_URL', 'http://localhost:8000'), '/');
        $userId = Auth::id() ? (string)Auth::id() : 'guest_session';

        try {
            // Forward payloads directly to your FastAPI Python Microservice Endpoints
            switch ($agentKey) {
                case 'rag_agent':
                    $response = Http::timeout(30)->post("{$fastApiUrl}/api/ask", [
                        'question' => $request->message
                    ]);
                    break;

                case 'claim_agent':
                    $response = Http::timeout(30)->post("{$fastApiUrl}/api/evaluate-claim", [
                        'claim_id' => $request->message
                    ]);
                    break;

                case 'support_agent':
                default:
                    $response = Http::timeout(30)->post("{$fastApiUrl}/api/support", [
                        'user_id' => $userId,
                        'message' => $request->message
                    ]);
                    break;
            }

            // Process the microservice response payload
            if ($response->successful()) {
                $data = $response->json();
                $replyText = "";

                // Tailor the presentation string to match your agent type
                if ($agentKey === 'rag_agent') {
                    $replyText = $data['answer'] ?? 'No answer provided.';
                } elseif ($agentKey === 'claim_agent') {
                    $replyText = "<strong>Evaluation Complete!</strong><br>Recommendation: " . strtoupper($data['recommendation'] ?? 'None') . "<br>Reason: " . ($data['reason'] ?? 'N/A');
                } else {
                    $replyText = $data['reply'] ?? 'No response returned.';
                    if (($data['action'] ?? '') === 'escalate_to_human') {
                        $replyText .= "<br><br><em>⚠️ Sentiment Rule Trigger: This request has been logged and flagged automatically for manual supervisor review.</em>";
                    }
                }

                // Return payload while preserving your custom frontend metadata variables (name, color)
                return response()->json([
                    'reply' => $replyText,
                    'orchestrator_escalation' => $data['orchestrator_escalation'] ?? false,
                    'review_summary' => $data['review_summary'] ?? null,
                    'agent_meta' => [
                        'name' => $selectedAgent['name'],
                        'color' => $selectedAgent['color']
                    ]
                ]);
            }

            Log::error("FastAPI Error output body: " . $response->body());
            return response()->json(['reply' => "Sub-Agent system processed an invalid payload response format."], $response->status());
            
        } catch (\Exception $e) {
            Log::critical("Connection breakdown with FastAPI engine: " . $e->getMessage());
            return response()->json(['reply' => "Connection to Nexus Sub-Agent [{$selectedAgent['name']}] disrupted."], 500);
        }
    }
}