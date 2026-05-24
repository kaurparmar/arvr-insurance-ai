# ARVR Insurance & AI Nexus Platform

A high-performance, enterprise-grade insurance ecosystem combining a localized Laravel 12 portal, a VR-themed frontend experience, MongoDB Atlas, and a decoupled Python FastAPI Multi-Agent RAG (Retrieval-Augmented Generation) Orchestration Engine.

---

## 🚀 Project Architecture & Data Flow

The platform utilizes a decoupled, asynchronous microservices architecture. The PHP Laravel application acts as the client-facing gateway and administrative portal, while a Python FastAPI cluster handles deep neural computations, intent classification, and multi-agent workflow routing via the Orchestrator.



- **Laravel Gateway:** Client-facing Blade views and JS intercepts.
- **FastAPI Orchestrator:** The central "Brain" that classifies user intent, hydrates context from MongoDB, and dispatches tasks to specialized agents.
- **MongoDB Atlas:** Distributed cloud persistence for policies, claims, and AI assessment logs.

---

## ✨ Features & Functional Modules

### 1. AI Nexus Multi-Agent Engine
- **Orchestrator (nexus_orchestrator):** The central routing hub that analyzes input, hydrates context from MongoDB, and dispatches to the correct specialized agent.
- **Policy RAG Search (rag_agent):** Queries live policy parameters in MongoDB Atlas and evaluates criteria using real-time vector constraints.
- **Risk & Claim Assessment (claim_agent):** Processes incoming claims. If discrepancies are found, it triggers an Orchestrator Cascade Mode that automatically forces a background call to the review_agent to construct an administrative dashboard summary card.
- **Support & Sentiment Router (support_agent):** Analyzes conversational text, classifies intent, and triggers an escalate_to_human routing action if high negative sentiment is detected.
- **Bi-Directional Admin Sync:** Supervisor resolutions inside the administrative dashboard trigger an administrative sync payload, updating the system state machine configuration across all nodes.

### 2. Core Insurance Workflows
- **Policy Applications:** Dynamic application forms mapped through /policies/apply/{plan}.
- **Claims Ledger:** Self-service filing pipeline allowing consumers to upload documents and trace verification status.
- **Financial Gateways:** Secured checkout systems for recurring policy premiums and transaction records.

### 3. VR Experience Studio
- An immersive, localized virtual-reality-themed presentation landscape accessible at /vr designed to preview risk assessment metrics in simulated 3D coordinates.

---

## 🛠️ Installation & Clone Guide

### 1. Cloning the Repository
git clone https://github.com/your-username/arvr-insurance-nexus.git
cd arvr-insurance-nexus

### 2. Setting Up the AI Microservice
cd ai-backend
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt

Create a .env file in the ai-backend/ directory:
MONGODB_URI="mongodb+srv://<username>:<password>@cluster.mongodb.net/insurance_db"
GROQ_API_KEY="gsk_your_high_speed_inference_token"

Start the orchestrator engine:
uvicorn main:app --host 0.0.0.0 --port 8000 --reload

### 3. Setting Up the Laravel Portal
cd ../laravel-app
composer install
npm install
cp .env.example .env
php artisan key:generate

Update your local .env with the same database URI and set FASTAPI_URL=http://localhost:8000.

Compile assets and boot:
npm run dev
php artisan serve

---

## 🗺️ Canonical Production Route Manifest

| Route Pattern | Target Endpoint Action | Auth Policy Context |
| :--- | :--- | :--- |
| GET /ai-nexus | AIInsuranceController@index | Auth Protected |
| POST /ai-nexus/chat | AIInsuranceController@handleChat | Auth Protected |
| GET /admin/claims | ClaimResolutionController@index | Auth, Admin Exclusive |
| POST /admin/claims/{id}/resolve | ClaimResolutionController@resolve | Auth, Admin Exclusive |
| POST /admin/policies/{id}/approve | DashboardController@approvePolicy | Auth, Admin Exclusive |