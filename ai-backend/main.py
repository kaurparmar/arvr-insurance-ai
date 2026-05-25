import os
import traceback
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from dotenv import load_dotenv
import uvicorn

# Bring in the master agent manager and custom tool metrics
from orchestrator import nexus_orchestrator
from agents.review_agent import build_human_review_summary
from tools.mongo_tools import update_claim_assessment

load_dotenv()

app = FastAPI(title="ARVR Insurance AI Capstone Orchestrator Engine", version="2.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["https://arvr-insurance-igzh.onrender.com", "http://127.0.0.1:8000"], # Replace with specific live Railway URL patterns during deployment
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Unified Multi-Agent Input Schema
class NexusChatPayload(BaseModel):
    prompt: str
    context_id: str = None # Can seamlessly accept claim_id, user_id, or policy references

class HumanReviewPayload(BaseModel):
    claim_id: str
    recommendation: str
    reason: str

class UpdateClaimStatusPayload(BaseModel):
    claim_id: str
    status: str
    admin_note: str
@app.get("/")
async def root():
    return {"status": "online"}
# Endpoints Mapping
@app.get("/api/health")
def health_check():
    return {"status": "ok", "orchestrator_layer_active": True}

# --- THE SINGLE AGENTIC CONVERSATION ENTRY PATHWAY ---
@app.post("/api/nexus-chat")
def handle_agentic_conversation(payload: NexusChatPayload):
    try:
        if not payload.prompt.strip():
            raise HTTPException(status_code=400, detail="User message cannot be left blank.")
            
        # Dispatch prompt directly to our supervisor layer
        agent_response = nexus_orchestrator(
            user_prompt=payload.prompt, 
            context_id=payload.context_id
        )
        
        # If a downstream agent explicitly caught an operational check error, bounce it as a 400
        if isinstance(agent_response, dict) and "error" in agent_response:
            raise HTTPException(status_code=400, detail=agent_response["error"])
            
        return agent_response
        
    except HTTPException as http_ex:
        raise http_ex
    except Exception as e:
        print("❌ Critical Core Exception inside /api/nexus-chat execution context:")
        print(traceback.format_exc())
        raise HTTPException(status_code=500, detail=f"Core Agent Network breakdown: {str(e)}")

# Keep manual admin override hooks accessible for your frontend management dashboard layouts
@app.post("/api/flag-for-review")
def force_manual_review_card(payload: HumanReviewPayload):
    try:
        return build_human_review_summary(payload.claim_id, payload.recommendation, payload.reason)
    except Exception as e:
        print(traceback.format_exc())
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/update-claim-status")
def update_claim_status_endpoint(payload: UpdateClaimStatusPayload):
    try:
        update_claim_assessment(payload.claim_id, {
            "status": payload.status,
            "admin_advisory_note": payload.admin_note
        })
        return {"success": True}
    except Exception as e:
        print(traceback.format_exc())
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    # Render provides a PORT environment variable. If it's missing (local), default to 8000
    port = int(os.environ.get("PORT", 8000))
    # host must be 0.0.0.0 to be accessible outside the container
    uvicorn.run("main:app", host="0.0.0.0", port=port, reload=False)