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
        $fastApiUrl = rtrim(env('FASTAPI_URL', 'http://127.0.0.1:8010'), '/');
        $userId = Auth::id() ? (string)Auth::id() : 'guest_session';

        try {
            // Forward payloads directly to your FastAPI Python Microservice Endpoints
            // Added ->withoutVerifying() to prevent local SSL certificate loops
            switch ($agentKey) {
                case 'rag_agent':
                    $response = Http::withoutVerifying()->timeout(30)->post("{$fastApiUrl}/api/ask", [
                        'question' => $request->message
                    ]);
                    break;

                case 'claim_agent':
                    $response = Http::withoutVerifying()->timeout(30)->post("{$fastApiUrl}/api/evaluate-claim", [
                        'claim_id' => $request->message
                    ]);
                    break;

                case 'support_agent':
                default:
                    $response = Http::withoutVerifying()->timeout(30)->post("{$fastApiUrl}/api/support", [
                        'user_id' => $userId,
                        'message' => $request->message
                    ]);
                    break;
            }

            // Process the microservice response payload
            if ($response->successful()) {
                $data = $response->json();
                $replyText = "";

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

            // CRITICAL DEBUG: If FastAPI fails, return Python's exact error message to the browser console!
            Log::error("FastAPI Error output body: " . $response->body());
            return response()->json([
                'reply' => "Sub-Agent system processed an invalid payload response format.",
                'debug_fastapi_status' => $response->status(),
                'debug_fastapi_error' => $response->json() ?? $response->body()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::critical("Connection breakdown with FastAPI engine: " . $e->getMessage());
            return response()->json([
                'reply' => "Connection to Nexus Sub-Agent [{$selectedAgent['name']}] disrupted.",
                'debug_exception' => $e->getMessage(),
                'debug_file' => $e->getFile(),
                'debug_line' => $e->getLine()
            ], 500);
        }
    }

   public function handleChat(Request $request)
    {
        // 1. Validate the incoming text from your updated Blade view
        $request->validate([
            'prompt' => 'required|string',
        ]);

        try {
            // 2. Forward the prompt to your unified FastAPI Orchestrator endpoint
            $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8000') . '/api/nexus-chat';

            // Forward the user input context safely to Python
            $response = Http::timeout(30)->post($fastApiUrl, [
                'prompt' => $request->input('prompt'),
                'context_id' => auth()->check() ? (string)auth()->user()->id : null
            ]);

            if ($response->failed()) {
                return response()->json([
                    'reply' => '⚠️ **System Notice:** The FastAPI Orchestrator rejected the call or timed out.',
                    'intent_dispatched' => 'SUPPORT'
                ], 500);
            }

            // 3. Capture the pre-formatted dictionary straight from Python
            $data = $response->json();

            // Because our Python orchestrator already structured the formatting,
            // we just safely read and pass the variables right back down to JavaScript.
            return response()->json([
                'reply'             => $data['reply'] ?? '⚠️ Empty neural token array generated.',
                'intent_dispatched' => $data['intent_dispatched'] ?? 'SUPPORT'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'reply'             => '⚠️ **Uplink Failure:** Lost contact with the Nexus Core execution cluster. Check if your Uvicorn server is running.',
                'intent_dispatched' => 'SUPPORT'
            ], 500);
        }
    }
}