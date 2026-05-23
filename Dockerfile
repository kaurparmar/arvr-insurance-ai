FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    zip \
    libzip-dev \
    nodejs \
    npm \
    build-essential \
    gcc \
    g++ \
    make \
    cmake \
    autoconf \
    pkg-config \
    libssl-dev \
    libffi-dev \
    python3 \
    python3-pip \
    python3-dev \
    zlib1g-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Upgrade pip
RUN pip3 install --no-cache-dir --break-system-packages --upgrade pip setuptools wheel

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Frontend build
RUN npm install
RUN npm run build

# Python requirements
RUN pip3 install --no-cache-dir --break-system-packages -r ai_backend/requirements.txt

EXPOSE 8000

CMD sh -c "python3 -m uvicorn ai_backend.main:app --host 0.0.0.0 --port 8001 & php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"