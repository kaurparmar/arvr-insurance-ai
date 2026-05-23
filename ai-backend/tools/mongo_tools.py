import os
from bson import ObjectId
from pymongo import MongoClient
from dotenv import load_dotenv

load_dotenv()

MONGO_URI = os.getenv("MONGODB_URI")
DB_NAME = os.getenv("DB_NAME", "insurance_db")

def get_db_collection(collection_name: str):
    """Establishes thread connection to targeted MongoDB Atlas Cluster collection."""
    client = MongoClient(MONGO_URI)
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