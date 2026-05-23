import os
from langchain_groq import ChatGroq  
from langchain_huggingface import HuggingFaceEmbeddings  
from langchain_community.vectorstores import Chroma
from tools.rag_tools import CHROMA_PATH, retrieve_context

def execute_rag_search(question: str) -> dict:
    # 1. Initialize the free local vector lookup matching your ingest configuration
    embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")
    
    # 2. Grab context chunks using the free system pipeline
    context_chunks = retrieve_context(question, k=3)
    joined_context = "\n\n".join(context_chunks)
    
    system_prompt = (
        "You are an expert Insurance Policy Compliance Adjuster. Answer the customer's question accurate to "
        "the verified context source constraints provided below. If the context does not contain sufficient data, "
        "advise the user to contact an administrator. Cite specific plan names clearly.\n\n"
        "Verified Reference Rules:\n"
        f"{joined_context}"
    )
    
    # 3. Use Groq's free cloud inference for generation
    llm = ChatGroq(
        model="llama3-8b-8192",
        temperature=0.2,
        groq_api_key=os.getenv("GROQ_API_KEY")
    )
    
    # Invoke the model using LangChain's message structure format
    messages = [
        ("system", system_prompt),
        ("user", question)
    ]
    response = llm.invoke(messages)
    
    return {
        "answer": response.content,
        "sources": ["Policy Knowledge Base Document Excerpt"]
    }