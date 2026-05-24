import os
from langchain_community.document_loaders import TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from tools.rag_tools import CHROMA_PATH

def run_pipeline_ingestion():
    source_file = "claim_policy.txt"
    
    if not os.path.exists(source_file):
        print(f"❌ Error: {source_file} not found in the root directory!")
        return

    print(f"📖 Loading data from {source_file}...")
    loader = TextLoader(source_file)
    raw_documents = loader.load()

    # Split the corporate rules into small, manageable text segments
    print("✂️ Splitting document text into clean semantic chunks...")
    text_splitter = RecursiveCharacterTextSplitter(chunk_size=300, chunk_overlap=50)
    document_chunks = text_splitter.split_documents(raw_documents)

    # Initialize the exact same embedding model used by your RAG Agent
    print("🧠 Generating text vector embeddings via HuggingFace...")
    embeddings_model = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")

    # Save the vector representations directly into your persistent Chroma database path
    print(f"💾 Writing vector tokens to Chroma DB storage path: {CHROMA_PATH}...")
    db = Chroma.from_documents(
        document_chunks, 
        embeddings_model, 
        persist_directory=CHROMA_PATH
    )
    
    print("🚀 Ingestion successful! Your RAG Agent can now read these corporate rules.")

if __name__ == "__main__":
    run_pipeline_ingestion()