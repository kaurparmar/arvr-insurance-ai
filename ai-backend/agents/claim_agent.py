import os
from langchain_groq import ChatGroq
from tools.mongo_tools import get_claim_record, get_policy_record, update_claim_assessment

def evaluate_insurance_claim(claim_id: str) -> dict:
    claim = get_claim_record(claim_id)
    if not claim:
        return {"error": "Target claim identifier record could not be extracted."}
        
    # Input Validation Gate
    required_fields = ["policy_id", "claim_amount", "reason"]
    for field in required_fields:
        if field not in claim or claim[field] is None:
            return {"error": f"Validation Gate Aborted: Missing required field '{field}' in document."}

    policy = get_policy_record(claim["policy_id"])
    
    # Rule-Based Fraud Detection System
    risk_signals = []
    recommendation = "approve"
    reason_notes = "Automated baseline check registers details clear of policy violation thresholds."
    
    if not policy or policy.get("status") != "active":
        recommendation = "reject"
        risk_signals.append("Linked account configuration profile status is not active.")
        
    float_claim_amt = float(claim["claim_amount"])
    max_coverage = float(policy.get("max_coverage", 500000) if policy else 500000)
    
    if float_claim_amt > max_coverage:
        recommendation = "flag"
        risk_signals.append(f"Requested claim amount ({float_claim_amt}) exceeds coverage roof ({max_coverage}).")

    suspicious_keywords = ["deliberate", "intentional", "pre-existing", "altered", "fake"]
    if any(word in str(claim["reason"]).lower() for word in suspicious_keywords):
        recommendation = "flag"
        risk_signals.append("Text scan discovered restricted context risk terms.")

    # LLM-Driven Evaluator Reasoning (Using Free Smart 70B Model)
    llm = ChatGroq(
        model="llama3-70b-8192",
        temperature=0.3,
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    prompt = (
        f"Analyze this claim profile entry for risk assessment:\n"
        f"- Claim Reason Statement: {claim['reason']}\n"
        f"- Requested Cost: {claim['claim_amount']}\n"
        f"- Program Risk Flags: {', '.join(risk_signals) if risk_signals else 'None'}\n\n"
        f"Provide an analytical validation explanation under 60 words justifying why this transaction "
        f"should be resolved with the following status recommendation: '{recommendation}'."
    )

    messages = [
        ("system", "You are an automated insurance risk assessment service."),
        ("user", prompt)
    ]
    response = llm.invoke(messages)
    reason_notes = response.content
    
    db_updates = {
        "ai_recommendation": recommendation,
        "ai_analysis_reason": reason_notes,
        "risk_level": "HIGH" if recommendation in ["flag", "reject"] else "LOW",
        "status": "Under Review" if recommendation == "flag" else ("Approved" if recommendation == "approve" else "Rejected")
    }
    update_claim_assessment(claim_id, db_updates)
    
    return {
        "claim_id": claim_id,
        "recommendation": recommendation,
        "reason": reason_notes,
        "risk_level": db_updates["risk_level"]
    }