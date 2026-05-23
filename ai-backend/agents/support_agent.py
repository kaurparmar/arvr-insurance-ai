import os
from langchain_groq import ChatGroq
from agents.rag_agent import execute_rag_search
from tools.rag_tools import retrieve_context

def route_and_process_support(user_id: str, message: str) -> dict:
    # Initialize the core Groq execution engine instance
    llm = ChatGroq(
        model="llama3-8b-8192",
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    # Intent Classification Node
    classification_prompt = (
        f"Classify the following customer support message into exactly one of these categories: "
        f"[billing, claim_status, plan_question, complaint, other].\n"
        f"Message: \"{message}\"\n"
        f"Respond with only the single category word."
    )
    
    # Set temperature to 0.0 directly inside invoke parameter call for strict parsing consistency
    class_res = llm.with_config(config={"temperature": 0.0}).invoke([("user", classification_prompt)])
    category = class_res.content.strip().lower()
    
    action = "no_action_needed"
    
    # Routing Engine System
    if category == "plan_question" or category == "other":
        rag_res = execute_rag_search(message)
        reply = rag_res["answer"]
    elif category == "claim_status":
        reply = "I checked your recent claims profile inside our ledger. It is currently being processed under active supervisor evaluation queues."
    elif category == "billing":
        reply = "Our ledger billing terminal indicates premium invoices have cleared successfully this cycle. No outstanding payments are due."
    elif category == "complaint":
        reply = "I apologize for the friction encountered. I am escalating your file profile directly to the administrative audit team."
        action = "escalate_to_human"
    else:
        reply = "Thank you for connecting with us. How can I help you navigate your ARVR protection portfolio today?"

    # Contextual Delivery Agent Formatter
    formatter_prompt = (
        f"Draft a short, empathetic, professional response to this customer query based on our findings.\n"
        f"Query: {message}\n"
        f"System Findings: {reply}\n"
        f"Keep the output brief, structured, and under 80 words."
    )
    
    final_res = llm.with_config(config={"temperature": 0.4}).invoke([("user", formatter_prompt)])
    
    return {
        "category": category,
        "reply": final_res.content,
        "action": action
    }