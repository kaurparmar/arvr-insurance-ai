# ARVR Insurance & AI Nexus Platform

A high-performance, enterprise-grade insurance ecosystem combining a localized Laravel 12 portal, a VR-themed frontend experience, MongoDB Atlas, and a decoupled Python FastAPI Multi-Agent RAG (Retrieval-Augmented Generation) Orchestration Engine.

---

## 🚀 Project Architecture & Data Flow

The platform utilizes a decoupled, asynchronous microservices architecture. The PHP Laravel application acts as the client-facing gateway and administrative portal, while a Python FastAPI cluster handles deep neural computations, intent classification, and multi-agent workflow routing.

### System Topography & Component Network

[Client Layout / Blade Views / JS Intercepts]
               │
               ▼ (Encrypted HTTPS / JSON Payloads)
[Laravel Gateway Core / AIInsuranceController]
               │
               ▼ (Internal REST API Routing Pipeline)
[FastAPI Orchestrator Layer]
   ├── RAG Agent (Policy Vector Context)
   ├── Claim Evaluator (Auto-Flag Cascade Model)
   └── Support Classifier (Sentiment Detection Loop)
               │
               ▼ (Distributed Connection Pools)
[MongoDB Atlas Cloud Persistence Layer]

---

## ✨ Features & Functional Modules

### 1. AI Nexus Multi-Agent Engine
An advanced array of specialized sub-agents orchestrated by a centralized state router:
*   **Policy RAG Search (rag_agent):** Queries live policy parameters in MongoDB Atlas, evaluates criteria using real-time vector constraints, and streams answers back to the UI with verifiable source citations.
*   **Risk & Claim Assessment (claim_agent):** Processes incoming claims. If discrepancies are found (e.g., matching an inactive policy or exceeding limits), it triggers an Orchestrator Cascade Mode that automatically forces a background call to the review_agent to construct an administrative dashboard summary card.
*   **Support & Sentiment Router (support_agent):** Analyzes conversational text, classifies intent into functional sectors (billing, complaint, status), and triggers an escalate_to_human routing action code if high negative sentiment is detected.
*   **Bi-Directional Admin Sync:** When a supervisor signs off or rejects a flag inside the secure administrative dashboard, an administrative sync payload updates the state machine configuration across all systems.

### 2. Core Insurance Workflows
*   **Policy Applications:** Dynamic application forms mapped through /policies/apply/{plan} with robust field tracking.
*   **Claims Ledger:** Self-service filing pipeline allowing consumers to upload documents, trace verification status, and inspect tracking metrics.
*   **Financial Gateways:** Secured checkout systems for recurring policy premiums and transaction records at /transactions/{policy}/create`.

### 3. Multi-Language Localization
Enterprise translation arrays managed natively through standard JSON or directory structures:
*   resources/lang/en/messages.php (English Global Baseline)
*   resources/lang/hi/messages.php (Hindi Regional Settings)
*   resources/lang/pa/messages.php (Punjabi Regional Settings)

Dynamic locale changes are safely handled through the high-speed middleware route /lang/{locale}.

### 4. VR Experience Studio
*   An immersive, localized virtual-reality-themed presentation landscape accessible at /vr designed to preview risk assessment metrics in simulated 3D coordinates.

---

## 🛠️ Directory Blueprint

### Frontend Gateway (laravel-app/)
laravel-app/
├── app/Http/Controllers/
│   ├── AIInsuranceController.php       # Handles proxying to the FastAPI endpoints
│   └── Admin/
│       └── ClaimResolutionController.php # Human supervisor approval & backend sync engine
├── config/nexus.php                    # System UI configuration registry array
├── resources/views/ai-nexus.blade.php  # Chat UI view layer with dynamic agent injection
└── routes/web.php                      # Application secure route groups

### Python Neuro-Engine (ai-backend/)
ai-backend/
├── main.py                             # High-speed FastAPI API Orchestrator
├── Procfile                            # Production environment runtime script
├── agents/
│   ├── rag_agent.py                    # Knowledge-base search execution
│   ├── claim_agent.py                  # Auto-evaluation & fraud vector analysis
│   ├── support_agent.py                # Intent classifier & sentiment model gateway
│   └── review_agent.py                 # Executive human briefing card engine
└── tools/
    └── mongo_tools.py                  # Distributed connection pool layer

---

## ⚙️ Installation & Local Initialization

### 1. Setting Up the AI Microservice
cd ai-backend
python -m venv venv
source venv/bin/activate  # On Windows use `venv\Scripts\activate`
pip install -r requirements.txt

Create a .env file in the root of your ai-backend/ directory:
MONGODB_URI="mongodb+srv://<username>:<password>@cluster.mongodb.net/insurance_db"
GROQ_API_KEY="gsk_your_high_speed_inference_token"

Start the service engine:
uvicorn main:app --host 0.0.0.0 --port 8000 --reload

### 2. Setting Up the Laravel Portal
cd laravel-app
composer install
npm install
cp .env.example .env
php artisan key:generate

Update your local .env environment layout:
DB_CONNECTION=mongodb
MONGODB_URI="mongodb+srv://<username>:<password>@cluster.mongodb.net/insurance_db"
MONGODB_DATABASE=insurance_db
FASTAPI_URL=http://localhost:8000

Compile assets and boot the server:
npm run dev
php artisan serve

---

## 🌐 Production Deployment (Render / Railway)

### 1. Python FastAPI Backend (Railway/Render)
*   The project contains a production-ready Procfile targeting Uvicorn container bounds.
*   Ensure environment parameters for MONGODB_URI and GROQ_API_KEY are populated in your deployment environment variables.

### 2. PHP Laravel Web Service (Render/Docker)
*   Add the production URL of your running AI engine to your environment variables:
    FASTAPI_URL=https://your-ai-backend-service.onrender.com
*   Run production build optimization before staging deployment:
    npm run build
    php artisan config:cache
    php artisan route:cache

---

## 🧪 Testing Protocol

Run the test suites to verify system health:
php artisan test

*   Database Test Isolation Warning: Your phpunit.xml configuration should define DB_CONNECTION=mongodb and isolate data parameters into MONGODB_DATABASE=arvr-insurance_test. If execution routines yield errors such as SQLiteConnection::getCollection does not exist, verify that test runtimes are not defaulting to an unconfigured SQLite engine.

---

## 🗺️ Canonical Production Route Manifest

| Route Pattern | Target Endpoint Action | Auth Policy Context |
| :--- | :--- | :--- |
| GET / | PageController@welcome | Public Access |
| GET /vr | Render Immersive VR Sandbox Layout | Public Access |
| GET /ai-nexus | Multi-Agent Conversational Dashboard | auth Protected |
| POST /ai-nexus/chat/{agent} | Forward message packet to designated agent | auth Protected |
| GET /admin/claims | View flagged claims requiring review | auth, admin Exclusive |
| POST /admin/claims/{id}/resolve | Finalize supervisor approval and sync with AI backend | auth, admin Exclusive |

---

