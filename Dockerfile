# ========== STAGE 1: BUILDER (Install dependencies) ==========
FROM php:8.2-fpm-alpine AS builder

# Install build dependencies and Composer
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions needed for Composer
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY src /app

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction || true

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache || true


# ========== STAGE 2: RUNTIME (Final lightweight image) ==========
FROM php:8.2-fpm-alpine

# Install ONLY runtime dependencies (no Composer!)
RUN apk add --no-cache \
    curl \
    mysql-client \
    nginx \
    supervisor \
    libpng \
    libpng-dev \
    libzip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Set working directory
WORKDIR /var/www/html

# Copy ONLY installed application from builder stage
COPY --from=builder /app /var/www/html

# Create supervisor log directory
RUN mkdir -p /var/log/supervisor

# Create non-root user for security
RUN addgroup -g 1000 appuser && \
    adduser -D -u 1000 -G appuser appuser

# Set ownership (supervisor/nginx need root, but files owned by appuser)
RUN chown -R appuser:appuser /var/www/html && \
    chown -R appuser:appuser /var/log/supervisor

# Copy nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# Copy supervisor config (updated to run php-fpm as appuser)
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# Health check: Verify Nginx responds
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

# Supervisor runs as root but PHP-FPM runs as appuser (configured in supervisord.conf)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]