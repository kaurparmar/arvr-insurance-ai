FROM php:8.2-cli

# Install dependencies and build tools
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

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Build frontend assets
RUN npm run build

# Expose port
EXPOSE 8000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}