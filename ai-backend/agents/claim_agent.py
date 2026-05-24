import os
from langchain_groq import ChatGroq
from tools.mongo_tools import get_user_claims,get_claim_record, get_policy_record, update_claim_assessment

def evaluate_insurance_claim(claim_id: str) -> dict:
    """
    Evaluates an insurance claim document using a hybrid strategy:
    Rule-based processing checks for baseline constraints, and an LLM block
    (Llama-3.3-70b-versatile via Groq) generates structured analytical reasoning.
    """
    # 1. Structural Guardrail: Guard against missing or malformed identification tokens
    if not claim_id or len(str(claim_id).strip()) < 5:
        return {
            "claim_id": "Malformed_Identifier",
            "recommendation": "flag",
            "reason": "The evaluation system received an invalid or missing Claim ID format. Please input a valid record reference string.",
            "risk_level": "HIGH"
        }

    claim_id = str(claim_id).strip()
    claim = get_claim_record(claim_id)
    
    # 2. Database Miss Guardrail: Gracefully fall back if the ID is not found in MongoDB
    if not claim:
        return {
            "claim_id": claim_id,
            "recommendation": "flag",
            "reason": f"Target claim record '{claim_id}' could not be extracted from the database store. Verification aborted.",
            "risk_level": "HIGH"
        }
        
    # 💡 UPGRADED VALIDATION GATE: Gracefully flag missing fields instead of throwing a hard pipeline abort error
    required_fields = ["policy_id", "claim_amount", "reason"]
    missing_fields = [field for field in required_fields if field not in claim or claim[field] is None]
    
    risk_signals = []
    recommendation = "approve"

    if missing_fields:
        recommendation = "flag"
        risk_signals.append(f"Document integrity failure: Missing or null fields detected: {', '.join(missing_fields)}.")
        # Provide fallback values so downstream processing variables do not cause KeyError/TypeError crashes
        if "reason" in missing_fields:
            claim["reason"] = "[No description or reason provided in database document]"
        if "claim_amount" in missing_fields:
            claim["claim_amount"] = 0.0
        if "policy_id" in missing_fields:
            claim["policy_id"] = "None"

    # Fetch associated policy record
    policy = get_policy_record(claim["policy_id"]) if claim["policy_id"] != "None" else None
    
    # Rule-Based Fraud Detection System (Only evaluated if fields weren't completely missing)
    if not missing_fields:
        if not policy or policy.get("status") != "active":
            recommendation = "reject"
            risk_signals.append("Linked account configuration profile status is not active.")
            
        try:
            float_claim_amt = float(claim["claim_amount"])
            max_coverage = float(policy.get("max_coverage", 500000) if policy else 500000)
            
            if float_claim_amt > max_coverage:
                recommendation = "flag"
                risk_signals.append(f"Requested claim amount ({float_claim_amt}) exceeds coverage roof ({max_coverage}).")
        except (ValueError, TypeError):
            recommendation = "flag"
            risk_signals.append("Data type mismatch: Unable to convert claim amount or coverage limit to float numeric values.")

        suspicious_keywords = ["deliberate", "intentional", "pre-existing", "altered", "fake"]
        if any(word in str(claim["reason"]).lower() for word in suspicious_keywords):
            recommendation = "flag"
            risk_signals.append("Text scan discovered restricted context risk terms.")

    # 3. LLM-Driven Evaluator Reasoning
    llm = ChatGroq(
        model="llama-3.3-70b-versatile",
        temperature=0.1,  # Kept low for strict compliance
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    prompt = (
        f"Analyze this claim profile entry for risk assessment:\n"
        f"- Target Claim ID: {claim_id}\n"
        f"- Claim Reason Statement: {claim['reason']}\n"
        f"- Requested Cost: {claim['claim_amount']}\n"
        f"- Program Risk Flags: {', '.join(risk_signals) if risk_signals else 'None'}\n\n"
        f"STRICT INSTRUCTION: Provide an analytical validation explanation under 60 words justifying why this transaction "
        f"should be resolved with the following status recommendation: '{recommendation}'.\n"
        f"CRITICAL SAFETY RULE: You must reference the active target context. Do not output placeholder strings or old system tracking IDs."
    )

    messages = [
        ("system", "You are an automated insurance risk assessment service. You map parameters explicitly without using static sandbox dataset examples."),
        ("user", prompt)
    ]
    
    try:
        response = llm.invoke(messages)
        reason_notes = response.content.strip()
    except Exception as e:
        print(f"⚠️ [Claim Agent] Model inference dropped: {e}")
        reason_notes = ""

    # 🛡️ NEURAL TOKEN GUARDRAIL: Prevent empty string pass-throughs
    if not reason_notes:
        if recommendation == "flag":
            reason_notes = f"Target claim record '{claim_id}' flagged due to automatic risk criteria matching system profile limits or missing data metrics."
        elif recommendation == "reject":
            reason_notes = f"Transaction rejected automatically. Policy status matching failed or is inactive."
        else:
            reason_notes = "Claim verified successfully. Structural parameters conform to active system benchmarks."
    
    db_updates = {
        "ai_recommendation": recommendation,
        "ai_analysis_reason": reason_notes,
        "risk_level": "HIGH" if recommendation in ["flag", "reject"] else "LOW",
        "status": "Under Review" if recommendation == "flag" else ("Approved" if recommendation == "approve" else "Rejected")
    }
    
    try:
        update_claim_assessment(claim_id, db_updates)
    except Exception as db_err:
        print(f"⚠️ [Claim Agent] Could not write assessment metrics back to MongoDB: {db_err}")
    
    return {
        "claim_id": str(claim_id),  # Hardens data reference casting
        "recommendation": recommendation,
        "reason": reason_notes,
        "risk_level": db_updates["risk_level"]
    }