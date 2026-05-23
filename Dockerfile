FROM php:8.2-cli

# Install system dependencies, build tools, plus Python 3 and pip
# FIX: Added g++ explicitly to make sure chroma-hnswlib can compile its C++ binaries
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    nodejs \
    npm \
    build-essential \
    g++ \
    autoconf \
    pkg-config \
    libssl-dev \
    zlib1g-dev \
    python3 \
    python3-dev \
    python3-pip \
    python3-venv \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Build and enable MongoDB extension from GitHub source using release tarballs
RUN set -eux; \
    mkdir -p /tmp/mongodb /tmp/mongodb/src/libmongoc /tmp/mongodb/src/libmongocrypt /tmp/mongodb/tests/drivers-evergreen-tools; \
    curl -L --retry 3 --retry-delay 5 \
      https://github.com/mongodb/mongo-php-driver/archive/refs/tags/2.3.1.tar.gz | tar xz --strip-components=1 -C /tmp/mongodb; \
    curl -L --retry 3 --retry-delay 5 \
      https://github.com/mongodb/mongo-c-driver/archive/dcd43fb5a6481d65d1545c3174f381b4332422b0.tar.gz | tar xz --strip-components=1 -C /tmp/mongodb/src/libmongoc; \
    curl -L --retry 3 --retry-delay 5 \
      https://github.com/mongodb/libmongocrypt/archive/2a9c124a897e7af266cefae3cbc3bbe5c7e5bfa9.tar.gz | tar xz --strip-components=1 -C /tmp/mongodb/src/libmongocrypt; \
    curl -L --retry 3 --retry-delay 5 \
      https://github.com/mongodb-labs/drivers-evergreen-tools/archive/4b02eb0136a767b3c6bbdff32b3584c0ec6f5b6f.tar.gz | tar xz --strip-components=1 -C /tmp/mongodb/tests/drivers-evergreen-tools; \
    cd /tmp/mongodb; \
    phpize; \
    ./configure; \
    make -j"$(nproc)"; \
    make install; \
    docker-php-ext-enable mongodb; \
    rm -rf /tmp/mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files (includes both Laravel project and ai-backend directory)
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies and build assets
RUN npm install
RUN npm run build

# --- AI Backend Setup Layer ---
# Set environment flags to prevent warning crashes on cloud architectures
ENV PYTHONUNBUFFERED=1
ENV HF_HUB_DISABLE_SYMLINKS_WARNING=1

# FIX: Force-update pip and wheel tools first. This gives Python 3.13 the blueprint
# it needs to compile older legacy packages like chroma-hnswlib without throwing errors.
RUN pip3 install --no-cache-dir --break-system-packages --upgrade pip setuptools wheel

# Install Python requirements globally inside the container environment
RUN pip3 install --no-cache-dir --break-system-packages -r ai-backend/requirements.txt

# Run the 100% free vector ingestion phase during image construction
RUN python3 ai-backend/tools/rag_tools.py --ingest

# Expose Laravel public traffic port
EXPOSE 8000

# Write a start shell execution string to boot BOTH systems simultaneously
CMD python3 -m uvicorn ai-backend.main:app --host 0.0.0.0 --port 8001 & php artisan serve --host=0.0.0.0 --port=${PORT:-8000}