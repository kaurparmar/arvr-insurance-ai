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
    The Central Brain of Nexus AI. Now includes Keyword-First routing 
    to ensure precise intent dispatching.
    """
    user_tracking_id = context_id if context_id else "anonymous_user"
    
    # 1. Context Hydration
    live_policies = get_user_policies(user_tracking_id)
    live_claims = get_user_claims(user_tracking_id)
    
    # 2. Enhanced Intent Routing
    prompt_lower = user_prompt.lower()
    has_hex_id = bool(re.search(r"\b[0-9a-fA-F]{24}\b", user_prompt))
    
    # Priority 1: Keyword-based routing (Prevents LLM misclassification)
    if any(word in prompt_lower for word in ["policy", "coverage", "plan", "status of my", "my account"]):
        intent = "RAG"
    elif has_hex_id or any(word in prompt_lower for word in ["claim", "filed a claim", "claim status"]):
        intent = "CLAIM"
    else:
        # Priority 2: LLM Classification fallback
        try:
            llm = ChatGroq(model="llama-3.1-8b-instant", temperature=0.0, groq_api_key=os.getenv("GROQ_API_KEY"))
            routing_instruction = "Respond with exactly ONE word ('CLAIM', 'RAG', or 'SUPPORT')."
            intent = llm.invoke([("system", routing_instruction), ("user", f"Classify: '{user_prompt}'")]).content.strip().upper()
        except:
            intent = "SUPPORT"

    # 3. Execution Blocks
    if intent == "CLAIM":
        hex_match = re.search(r"\b[0-9a-fA-F]{24}\b", user_prompt)
        target_claim_id = hex_match.group(0) if hex_match else get_user_latest_claim_id(user_tracking_id)
        
        if not target_claim_id:
            return {"reply": "⚠️ I couldn't find an active claim in your account. Please provide your Claim ID.", "intent_dispatched": "CLAIM"}
            
        result = evaluate_insurance_claim(target_claim_id)
        
        # Admin escalation
        if result.get("recommendation") == "flag":
            try:
                build_human_review_summary(target_claim_id, result["recommendation"], result.get("reason", "Flagged for review"))
            except Exception as e:
                print(f"⚠️ [Orchestrator] Escalation failed: {e}")

        # Dynamic Response
        reply_content = result.get("agent_response", result.get("reason", "Your claim is currently being processed."))
        reply = f"### 📋 Update on Claim `{target_claim_id}`\n\n{reply_content}"
        return {"reply": reply, "intent_dispatched": "CLAIM"}

    elif intent == "RAG":
        # Ensure context is explicitly passed to RAG agent
        rag_context = f"User Policies:\n{live_policies}\n\nQuery: {user_prompt}"
        res = execute_rag_search(rag_context)
        return {"reply": res.get("reply") if isinstance(res, dict) else res, "intent_dispatched": "RAG"}

    else:
        # Support Node
        support_context = f"Policies: {live_policies}\nClaims: {live_claims}\nQuery: {user_prompt}"
        support_result = route_and_process_support(user_tracking_id, support_context)
        return {"reply": support_result.get("reply") if isinstance(support_result, dict) else support_result, "intent_dispatched": "SUPPORT"}