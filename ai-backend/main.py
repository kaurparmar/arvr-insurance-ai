import os
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from dotenv import load_dotenv
import uvicorn
from agents.rag_agent import execute_rag_search
from agents.claim_agent import evaluate_insurance_claim
from agents.support_agent import route_and_process_support
from agents.review_agent import build_human_review_summary
from tools.mongo_tools import update_claim_assessment

load_dotenv()

app = FastAPI(title="ARVR Insurance AI Capstone Orchestrator Engine", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Replace with specific live Railway URL patterns during deployment
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Pydantic Schema Definitions
class RagQuery(BaseModel):
    question: str

class ClaimEvaluationQuery(BaseModel):
    claim_id: str

class SupportQuery(BaseModel):
    user_id: str
    message: str

class HumanReviewPayload(BaseModel):
    claim_id: str
    recommendation: str
    reason: str

class UpdateClaimStatusPayload(BaseModel):
    claim_id: str
    status: str
    admin_note: str

# Endpoints Mapping
@app.get("/api/health")
def health_check():
    return {"status": "ok", "agents": ["rag", "claim", "support", "review"]}

@app.post("/api/ask")
def ask_rag_policy(payload: RagQuery):
    try:
        return execute_rag_search(payload.question)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/evaluate-claim")
def process_claim_evaluation(payload: ClaimEvaluationQuery):
    result = evaluate_insurance_claim(payload.claim_id)
    if "error" in result:
        raise HTTPException(status_code=400, detail=result["error"])
    
    # --- Orchestrator Workflow Pattern ---
    # If Agent 2 flags the claim, automatically trigger Agent 4 to generate the review card
    if result.get("recommendation") == "flag":
        review_card = build_human_review_summary(
            claim_id=payload.claim_id,
            ai_recommendation=result["recommendation"],
            reason=result["reason"]
        )
        result["orchestrator_escalation"] = True
        result["review_summary"] = review_card["summary"]
    return result

@app.post("/api/support")
def process_customer_support(payload: SupportQuery):
    try:
        return route_and_process_support(payload.user_id, payload.message)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/flag-for-review")
def force_manual_review_card(payload: HumanReviewPayload):
    try:
        return build_human_review_summary(payload.claim_id, payload.recommendation, payload.reason)
    except Exception as e:
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
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    
    uvicorn.run("main:app", host="0.0.0.0", port=8000, reload=True)