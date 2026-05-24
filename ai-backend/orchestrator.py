import os
import re
import traceback
from langchain_groq import ChatGroq
from agents.rag_agent import execute_rag_search
from agents.claim_agent import evaluate_insurance_claim
from agents.support_agent import route_and_process_support
from agents.review_agent import build_human_review_summary
from tools.mongo_tools import get_user_policies, get_user_claims, get_user_latest_claim_id

def nexus_orchestrator(user_prompt: str, context_id: str = None) -> dict:
    """
    The Central Brain of Nexus AI. Dynamically routes requests and handles 
    context hydration with full logging and escalation workflows.
    """
    user_tracking_id = context_id if context_id else "anonymous_user"
    
    # 1. Context Hydration
    live_policies = get_user_policies(user_tracking_id)
    live_claims = get_user_claims(user_tracking_id)
    
    llm = ChatGroq(
        model="llama-3.1-8b-instant",
        temperature=0.0, 
        groq_api_key=os.getenv("GROQ_API_KEY")
    )

    # 2. Intent Routing
    has_hex_id = bool(re.search(r"\b[0-9a-fA-F]{24}\b", user_prompt))
    routing_instruction = "Respond with exactly ONE word ('CLAIM', 'RAG', or 'SUPPORT')."
    messages = [("system", routing_instruction), ("user", f"Classify: '{user_prompt}'")]
    
    try:
        intent = "CLAIM" if has_hex_id else llm.invoke(messages).content.strip().upper()
    except:
        intent = "SUPPORT"

    # 3. Execution Blocks
    if "CLAIM" in intent:
        # 1. Resolve target ID securely
        hex_match = re.search(r"\b[0-9a-fA-F]{24}\b", user_prompt)
        target_claim_id = hex_match.group(0) if hex_match else get_user_latest_claim_id(user_tracking_id)
        
        if not target_claim_id:
            return {"reply": "⚠️ I couldn't find an active claim in your account. Please provide your Claim ID.", "intent_dispatched": "CLAIM"}
            
        # 2. Run Internal Assessment & Logging
        result = evaluate_insurance_claim(target_claim_id)
        
        # 3. Maintain Internal Admin Escalation (Hidden from user)
        if result.get("recommendation") == "flag":
            try:
                build_human_review_summary(target_claim_id, result["recommendation"], result["reason"])
            except Exception as e:
                print(f"⚠️ [Orchestrator] Admin escalation logging failed: {e}")

        # 4. Generate User-Facing Response
        # The user sees a polished update; the "Admin Card" stays in the database.
        status_text = "being reviewed by our team" if result.get("recommendation") != "approve" else "processed"
        
        reply = (
            f"### 📋 Update on Claim `{target_claim_id}`\n\n"
            f"Thank you for your patience. Your claim status is currently: **{status_text.upper()}**.\n\n"
            f"**Details:**\n"
            f"• {result.get('reason', 'We are currently verifying the information provided.')}\n\n"
            f"**Recommended Next Steps:**\n"
            f"1. Log in to your user dashboard to ensure all documentation is complete.\n"
            f"2. If you have already updated your details, our team will reach out within 24-48 hours.\n\n"
            f"*Need further assistance? Just let me know!*"
        )
        
        return {"reply": reply, "intent_dispatched": "CLAIM"}
    elif "RAG" in intent:
        res = execute_rag_search(f"Context:\n{live_policies}\n\nQuery: {user_prompt}")
        return {"reply": res.get("reply") if isinstance(res, dict) else res, "intent_dispatched": "RAG"}

    else:
        # 4. Support Node (Restored Context Hydration)
        support_result = route_and_process_support(user_tracking_id, f"Policies: {live_policies}\nClaims: {live_claims}\nQuery: {user_prompt}")
        return {"reply": support_result.get("reply") if isinstance(support_result, dict) else support_result, "intent_dispatched": "SUPPORT"}