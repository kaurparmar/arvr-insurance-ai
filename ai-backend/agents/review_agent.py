import os
from langchain_groq import ChatGroq
from tools.mongo_tools import get_claim_record, update_claim_assessment

def build_human_review_summary(claim_id: str, ai_recommendation: str, reason: str) -> dict:
    claim = get_claim_record(claim_id)
    
    llm = ChatGroq(
        model="llama3-8b-8192",
        temperature=0.2,
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    prompt = (
        f"Generate a clear, structured Markdown summary card for a human claims admin dashboard based on this flagged profile:\n"
        f"- Claim ID Reference: {claim_id}\n"
        f"- Claim Stated Content: {claim.get('reason', 'N/A') if claim else 'N/A'}\n"
        f"- Automated Flag Reason: {reason}\n"
        f"- AI Security Advice: {ai_recommendation}\n\n"
        f"Structure your response exactly with these bullet titles:\n"
        f"1) Key case parameters\n"
        f"2) Core liability risks\n"
        f"3) Step-by-step verification requirements"
    )
    
    response = llm.invoke([("user", prompt)])
    card_markdown = response.content
    
    update_claim_assessment(claim_id, {"review_summary": card_markdown, "status": "Flagged For Review"})
    
    return {
        "summary": card_markdown,
        "review_id": claim_id
    }