FROM php:8.2-cli

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

RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV PIP_DEFAULT_TIMEOUT=100
ENV PYTHONDONTWRITEBYTECODE=1

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN pip3 install --upgrade pip setuptools wheel --break-system-packages

RUN pip3 install --no-cache-dir --break-system-packages -r ai-backend/requirements.txt

EXPOSE 8000

CMD sh -c "python3 -m uvicorn main:app --app-dir ai-backend --host 0.0.0.0 --port 8001 & php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"