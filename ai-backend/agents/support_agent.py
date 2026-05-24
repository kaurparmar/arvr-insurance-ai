import os
import re
from langchain_groq import ChatGroq
from agents.rag_agent import execute_rag_search
from tools.rag_tools import retrieve_context

def route_and_process_support(user_id: str, message: str) -> dict:
    # Initialize the core Groq execution engine instance
    # Upgraded from 8b to 70b to prevent empty token arrays during nested generation tasks
    llm = ChatGroq(
        model="llama-3.3-70b-versatile",
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    # Clean up the query string for classification by stripping off the database snapshot header if present
    clean_user_query = message
    if "User Input:" in message:
        match = re.search(r"User Input:\s*(.*)", message, re.DOTALL)
        if match:
            clean_user_query = match.group(1).strip()

    # Intent Classification Node
    classification_prompt = (
        f"Classify the following customer support message into exactly one of these categories: "
        f"[billing, claim_status, plan_question, complaint, other].\n"
        f"Message: \"{clean_user_query}\"\n"
        f"Respond with only the single category word."
    )
    
    # Set temperature to 0.0 directly inside invoke parameter call for strict parsing consistency
    class_res = llm.with_config(config={"temperature": 0.0}).invoke([("user", classification_prompt)])
    category = class_res.content.strip().lower()
    
    action = "no_action_needed"
    reply = ""
    
    # ─── REWORKED ROUTING ENGINE SYSTEM ──────────────────────────────────
    
    if category in ["plan_question", "other"]:
        try:
            rag_res = execute_rag_search(message)
            if isinstance(rag_res, dict):
                reply = rag_res.get("answer") or rag_res.get("reply") or str(rag_res)
            else:
                reply = rag_res
        except Exception as e:
            print(f"⚠️ [Support Agent] RAG sub-routing runtime failure: {e}")
            reply = ""
            
        # 🛡️ FALLBACK GUARD: Prevent empty token payload generations to the final LLM formatter
        if not reply or str(reply).strip() == "" or reply is None:
            reply = "Our standard insurance underwriting applications and policy document reviews typically take between 3 to 5 business days."

    elif category in ["claim_status", "billing"]:
        # We instruct the LLM to pull the real data from the database profile snapshot passed down by the orchestrator
        extraction_prompt = (
            f"You are a backend data extractor. Read this raw system context block and answer the user query based ONLY on what is written inside it.\n\n"
            f"{message}\n\n"
            f"CRITICAL INSTRUCTIONS:\n"
            f"1. If the user asks about policies, read ONLY the 'User Active Policies' section. Do not look at claims.\n"
            f"2. If the user asks about claims, read ONLY the 'User Filed Claims' section.\n"
            f"3. State exactly what is found. If a section says 'No records found' or is empty, tell the user clearly that no active records exist for that request."
        )
        try:
            extraction_res = llm.with_config(config={"temperature": 0.0}).invoke([("user", extraction_prompt)])
            reply = extraction_res.content.strip()
        except Exception as e:
            print(f"⚠️ [Support Agent] Data snapshot extraction failed: {e}")
            reply = "I was unable to pull your profile metrics from our cluster registries at this second."

    elif category == "complaint":
        reply = "I apologize for any friction you have encountered. I am escalating your profile log parameters directly to our administrative management audit team for priority evaluation."
        action = "escalate_to_human"

    else:
        reply = "Thank you for connecting with Nexus AI support. How can I help you navigate your protection portfolio today?"

    # Double check to ensure the system findings string variable is never passed empty to the final block
    if not reply or str(reply).strip() == "":
        reply = "I'm processing your account overview request, but could not retrieve matching system indicators."

    # ─── CONTEXTUAL DELIVERY FORMATTER ──────────────────────────────────
    formatter_prompt = (
        f"You are the chat interface for Nexus AI. Convert the following System Findings into a conversational message to answer the User Query.\n\n"
        f"User Query: {clean_user_query}\n"
        f"System Findings: {reply}\n\n"
        f"STRICT OUTPUT RULES:\n"
        f"1. Respond directly as a conversational chat message. Never write it like an email or letter.\n"
        f"2. NEVER use email elements like 'Dear Customer', 'Best regards', or sign-offs like '[Your Name]'.\n"
        f"3. Do not invent any extra data or placeholders.\n"
        f"4. Keep the output brief, structured, and under 80 words."
    )
    
    final_res = llm.with_config(config={"temperature": 0.2}).invoke([("user", formatter_prompt)])
    
    return {
        "category": category,
        "reply": final_res.content.strip(),
        "action": action
    }