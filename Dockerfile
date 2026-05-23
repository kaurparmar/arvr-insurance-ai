FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
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
    python3 \
    python3-pip \
    python3-dev \
    libffi-dev \
    libssl-dev \
    zlib1g-dev \
    && docker-php-ext-install zip

# Install MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Environment variables
ENV PIP_DEFAULT_TIMEOUT=100
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Working directory
WORKDIR /app

# Copy project files
COPY . .

# Laravel setup
RUN composer install --no-dev --optimize-autoloader

# Frontend assets
RUN npm install && npm run build

# Install Python dependencies
RUN pip3 install --no-cache-dir --break-system-packages -r ai-backend/requirements.txt

# Expose Laravel port
EXPOSE 8000

# Start both FastAPI and Laravel
CMD sh -c "python3 -m uvicorn main:app --app-dir ai-backend --host 0.0.0.0 --port 8001 & php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"