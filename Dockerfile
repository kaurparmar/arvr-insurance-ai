FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    nodejs \
    npm \
    build-essential \
    autoconf \
    pkg-config \
    libssl-dev \
    zlib1g-dev \
    python3 \
    python3-pip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# 🔥 FIX: Install and register the PHP MongoDB extension required by your project
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Fix: Ensure pip, setuptools, and wheel upgrade smoothly ignoring Debian blocks
RUN pip3 install --no-cache-dir --break-system-packages --ignore-installed --upgrade pip setuptools wheel

# Install PHP Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install Laravel dependencies & Build frontend assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Install Python requirements cleanly
RUN pip3 install --no-cache-dir --break-system-packages -r ai-backend/requirements.txt

EXPOSE 8000

CMD python3 -m uvicorn ai-backend.main:app --host 0.0.0.0 --port 8001 & php artisan serve --host=0.0.0.0 --port=${PORT:-8000}