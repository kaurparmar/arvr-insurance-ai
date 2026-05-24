import os
from langchain_groq import ChatGroq
from tools.mongo_tools import get_claim_record, update_claim_assessment

def build_human_review_summary(claim_id: str, ai_recommendation: str, reason: str) -> dict:
    claim = get_claim_record(claim_id)
    
    # 💡 FIX 1: Set temperature to 0.0 to prevent the LLM from wandering back to its training templates
    llm = ChatGroq(
        model="llama-3.1-8b-instant",
        temperature=0.0,
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    # 💡 FIX 2: Added structural negative guardrails directly into the instruction payload
    prompt = (
        f"Generate a clear, structured Markdown summary card for a human claims admin dashboard based on this flagged profile:\n\n"
        f"DATA INPUTS:\n"
        f"- Claim ID Reference: {claim_id}\n"
        f"- Claim Stated Content: {claim.get('reason', 'N/A') if claim else 'N/A'}\n"
        f"- Automated Flag Reason: {reason}\n"
        f"- AI Security Advice: {ai_recommendation}\n\n"
        f"STRICT FORMATTING RULES:\n"
        f"Structure your response exactly with these bullet titles:\n"
        f"1. Key case parameters\n"
        f"2. Core liability risks\n"
        f"3. Step-by-step verification requirements\n\n"
        f"🚨 CRITICAL SYSTEM GUARDRAIL:\n"
        f"Do not hallucinate, mimic old examples, or use the database string '6a11fb984059d183f1007662'. "
        f"You are strictly required to output the exact input Claim ID Reference provided above: '{claim_id}'."
    )
    
    try:
        response = llm.invoke([("user", prompt)])
        card_markdown = response.content.strip()
    except Exception as e:
        print(f"⚠️ [Review Agent] Generation dropped link context: {e}")
        card_markdown = (
            f"1. Key case parameters\n"
            f"Claim ID Reference: {claim_id}\n"
            f"Claim Stated Content: N/A\n"
            f"Automated Flag Reason: {reason}"
        )
    
    # Safeguard the update step so the application doesn't completely crash if Atlas throws a transient write drop
    try:
        update_claim_assessment(claim_id, {"review_summary": card_markdown, "status": "Flagged For Review"})
    except Exception as db_err:
        print(f"⚠️ [Review Agent] Could not sync states back to DB: {db_err}")
    
    return {
        "summary": card_markdown,
        "review_id": claim_id
    }