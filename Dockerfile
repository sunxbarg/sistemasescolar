# Etapa 1: Imagen base ligera con PHP-FPM y Nginx
FROM php:8.1-fpm-alpine AS base

# Instalar dependencias del sistema
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    && apk add --no-cache \
    nginx \
    supervisor \
    curl \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# Etapa 2: Configuración y aplicación
FROM base AS production

# # Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de configuración primero (mejor cache de layers)
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar archivos del proyecto
COPY . /var/www/html/

# Configurar permisos y directorios en una sola capa
RUN mkdir -p /var/www/html/php/uploads/perfiles \
    /var/log/supervisor \
    /var/log/nginx \
    /run/nginx \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/php/uploads \
    && chown -R nginx:nginx /var/log/nginx \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Configurar PHP-FPM para trabajar mejor con Nginx
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = off" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "decorate_workers_output = no" >> /usr/local/etc/php-fpm.d/www.conf

# # Exponer puerto 80
EXPOSE 80

# Healthcheck para verificar que el contenedor esté funcionando
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Comando por defecto usando Supervisor (se ejecuta como root para manejar nginx y php-fpm)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
