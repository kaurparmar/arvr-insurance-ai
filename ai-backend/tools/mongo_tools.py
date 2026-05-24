import os
from bson import ObjectId
from pymongo import MongoClient
from dotenv import load_dotenv

load_dotenv()

MONGODB_URI = os.getenv("MONGODB_URI", "").strip()
DB_NAME = os.getenv("DB_NAME", "arvr-insurance")

def get_db_collection(collection_name: str):
    if not MONGODB_URI:
        raise ValueError("Critical Error: MONGODB_URI is missing or not loaded from your .env file!")
        
    client = MongoClient(MONGODB_URI)
    
    # FIX: Explicitly bypass get_default_database() to prevent Atlas from wandering into 'test'
    db = client[DB_NAME]
    return db[collection_name]

def get_claim_record(claim_id: str):
    """Queries a single insurance claim tracking document by its Hex ID."""
    try:
        col = get_db_collection("claims")
        return col.find_one({"_id": ObjectId(claim_id)})
    except Exception:
        col = get_db_collection("claims")
        return col.find_one({"id_str": claim_id})

def get_policy_record(policy_id: str):
    """Queries active user coverage policy schema details."""
    try:
        col = get_db_collection("policies")
        return col.find_one({"_id": ObjectId(policy_id)})
    except Exception:
        col = get_db_collection("policies")
        return col.find_one({"id_str": policy_id})

def update_claim_assessment(claim_id: str, updates: dict):
    """Updates AI operational flags, status tracking, or review cards."""
    col = get_db_collection("claims")
    try:
        col.update_one({"_id": ObjectId(claim_id)}, {"$set": updates})
    except Exception:
        col.update_one({"id_str": claim_id}, {"$set": updates})

# ==============================================================
# LIVE USER PORTFOLIO EXTENSION - CORE CONTEXT ENGINE FOR AI
# ==============================================================

def get_user_policies(user_id: str):
    """Fetches all active/pending policies for a given user from MongoDB, handling mixed type casting."""
    try:
        col = get_db_collection("policies")
        
        # 🧪 TEMP DIAGNOSTIC: Print a sample document to terminal
        sample_doc = col.find_one()
        print("\n🔎 [DIAGNOSTIC] Sample Policy Document from DB:\n", sample_doc, "\n")
        print(f"🔎 [DIAGNOSTIC] Looking for user_id value: '{user_id}' (Type: {type(user_id)})\n")
        
        query_conditions = [{"user_id": user_id}]
        if ObjectId.is_valid(user_id):
            query_conditions.append({"user_id": ObjectId(user_id)})
            
        policies = list(col.find({"$or": query_conditions}))
        print(f"🔎 [DIAGNOSTIC] Total Documents Found: {len(policies)}")
            
        formatted_policies = []
        plans_col = get_db_collection("plans")
        
        for p in policies:
            plan_id = p.get("plan_id")
            plan_name = "Standard Plan Reference"
            if plan_id:
                plan_doc = None
                if ObjectId.is_valid(str(plan_id)):
                    plan_doc = plans_col.find_one({"_id": ObjectId(str(plan_id))})
                if not plan_doc:
                    plan_doc = plans_col.find_one({"_id": plan_id})
                    
                if plan_doc:
                    plan_name = plan_doc.get("name", "Custom Reference Plan")

            formatted_policies.append(
                f"- Plan: {plan_name} | Status: {p.get('status')} | Premium Paid: ₹{p.get('premium_paid', 0)} | Next Due: {p.get('next_due_date', '—')}"
            )
        return "\n".join(formatted_policies) if formatted_policies else "  - No active policy records found."
    except Exception as e:
        return f"Warning (User Policies Lookup Failed): {str(e)}"

def get_user_claims(user_id: str):
    """Fetches all claim tracking history sheets submitted by this specific user handling mixed casting safely."""
    try:
        col = get_db_collection("claims")
        
        query_conditions = [{"user_id": user_id}]
        if ObjectId.is_valid(user_id):
            query_conditions.append({"user_id": ObjectId(user_id)})
            
        claims = list(col.find({"$or": query_conditions}))
            
        formatted_claims = []
        for c in claims:
            formatted_claims.append(
                f"- Claim Ref: {c.get('claim_number', 'N/A')} | Status: {c.get('status')} | Amount Claimed: ₹{c.get('claim_amount', 0)} | Notes: {c.get('notes', 'None')}"
            )
        return "\n".join(formatted_claims) if formatted_claims else "  - No prior claim submission records found."
    except Exception as e:
        return f"Warning (User Claims Lookup Failed): {str(e)}"
def get_user_latest_claim_id(user_id: str):
    """Fetches the raw 24-char MongoDB ID for the most recent claim."""
    col = get_db_collection("claims")
    # Fetch the most recent claim record for the user
    query = {"user_id": user_id}
    if ObjectId.is_valid(user_id):
        query = {"$or": [{"user_id": user_id}, {"user_id": ObjectId(user_id)}]}
        
    latest_claim = col.find_one(query, sort=[('_id', -1)])
    return str(latest_claim["_id"]) if latest_claim else None