import os
import sys
import traceback
from langchain_community.document_loaders import DirectoryLoader, TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma

CHROMA_PATH = os.path.join(os.path.dirname(__file__), "../chroma_db")
DATA_PATH = os.path.join(os.path.dirname(__file__), "../data/policies")

def get_vector_store():
    """Initializes and returns the persistent Chroma client instance using free local models."""
    embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")
    try:
        # Attempt to load existing vector store
        return Chroma(persist_directory=CHROMA_PATH, embedding_function=embeddings)
    except Exception as e:
        print(f"❌ CHROMA INITIALIZATION CRASH: {str(e)}")
        print(traceback.format_exc())
        raise e
    # return Chroma(persist_directory=CHROMA_PATH, embedding_function=embeddings)

def ingest_knowledge_base():
    """Reads policy text docs, splits into semantic chunks, and updates vector store for free."""
    if not os.path.exists(DATA_PATH) or not os.listdir(DATA_PATH):
        print(f"[-] Data path '{DATA_PATH}' is empty. Add policy documents first.")
        return

    print("[+] Loading knowledge base text profiles...")
    loader = DirectoryLoader(DATA_PATH, glob="*.txt", loader_cls=TextLoader)
    documents = loader.load()

    text_splitter = RecursiveCharacterTextSplitter(chunk_size=500, chunk_overlap=50)
    chunks = text_splitter.split_documents(documents)
    
    print(f"[+] Processing {len(chunks)} chunks into local vector database store...")
    
    embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")
    db = Chroma.from_documents(chunks, embeddings, persist_directory=CHROMA_PATH)
    print("[+] Synchronization complete locally (100% free!).")

def retrieve_context(query: str, k: int = 3):
    """Queries persistent vector store to extract relevant background context strings."""
    db = get_vector_store()
    results = db.similarity_search(query, k=k)
    return [doc.page_content for doc in results]

if __name__ == "__main__":
    if len(sys.argv) > 1 and sys.argv[1] == "--ingest":
        ingest_knowledge_base()