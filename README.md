# Sistema Escolar - Configuración Docker

Este proyecto incluye configuración completa de Docker para ejecutar la aplicación localmente.

## Prerrequisitos

- Docker
- Docker Compose

## Instalación y Ejecución

### 1. Clonar el repositório (si aplica)
```bash
git clone <tu-repositorio>
cd sistemasescolar
```

### 2. Construir y ejecutar los contenedores
```bash
# Construir y levantar todos los servicios
docker-compose up -d --build

# O solo levantar (si ya están construidos)
docker-compose up -d
```

### 3. Acceder a la aplicación

- **Aplicación web**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
  - Usuario: `root`
  - Contraseña: `rootpassword`

## Servicios incluidos

### Web (PHP + Apache)
- Puerto: 8080
- Contiene la aplicación PHP
- Apache configurado para servir archivos PHP
- Extensiones MySQL instaladas

### Base de datos (MariaDB)
- Puerto: 3306
- Base de datos: `mi_proyecto`
- Usuario: `root`
- Contraseña: `rootpassword`
- Inicialización automática con `mi_proyecto.sql`

### phpMyAdmin
- Puerto: 8081
- Interfaz web para administrar la base de datos

## Comandos útiles

```bash
# Ver logs de todos los servicios
docker-compose logs

# Ver logs de un servicio específico
docker-compose logs web
docker-compose logs db

# Parar todos los servicios
docker-compose down

# Parar y eliminar volúmenes (¡CUIDADO: elimina datos de la base!)
docker-compose down -v

# Reconstruir contenedores
docker-compose up --build

# Acceder al contenedor web
docker exec -it sistemasescolar_web bash

# Acceder al contenedor de base de datos
docker exec -it sistemasescolar_db mysql -u root -p
```

## Configuración de base de datos

### Para desarrollo con Docker
El proyecto incluye un archivo `php/db_conexion_docker.php` con la configuración para Docker. Para usar esta configuración, actualiza tus archivos PHP para incluir:

```php
include 'php/db_conexion_docker.php';
```

### Configuración actual vs Docker
- **Local**: `localhost`, usuario `root`, sin contraseña
- **Docker**: host `db`, usuario `root`, contraseña `rootpassword`

## Estructura de archivos Docker

```
├── Dockerfile              # Configuración del contenedor web
├── docker-compose.yml      # Orquestación de servicios
├── .dockerignore           # Archivos excluidos del contexto Docker
└── php/
    ├── db_conexion.php     # Configuración original
    └── db_conexion_docker.php  # Configuración para Docker
```

## Solución de problemas

### La base de datos no se inicializa
```bash
# Eliminar volúmenes y recrear
docker-compose down -v
docker-compose up -d --build
```

### Permisos de archivos
```bash
# Fijar permisos en el directorio uploads
docker exec sistemasescolar_web chown -R www-data:www-data /var/www/html/php/uploads
docker exec sistemasescolar_web chmod -R 777 /var/www/html/php/uploads
```

### Limpiar todo y empezar de nuevo
```bash
docker-compose down -v
docker system prune -a --volumes
docker-compose up -d --build
```

## 🚀 Optimizaciones de la Imagen Docker

### Imagen Ligera y Optimizada
- **Base**: `php:8.2-fpm-alpine` (Alpine Linux - ~80MB vs ~400MB de la imagen Apache)
- **Servidor Web**: Nginx + PHP-FPM (más eficiente que Apache+mod_php)
- **Multi-stage Build**: Optimización de capas para reducir tamaño final
- **Build Dependencies**: Se eliminan después de la instalación

### Mejoras de Rendimiento
- **Nginx**: Servidor web más ligero y eficiente
- **PHP-FPM**: Manejo optimizado de procesos PHP
- **Compresión Gzip**: Archivos comprimidos automáticamente
- **Cache de Archivos Estáticos**: Headers de cache optimizados
- **Healthcheck**: Monitoreo automático del contenedor

### Seguridad Mejorada
- Headers de seguridad configurados (XSS, CSRF, etc.)
- Acceso denegado a archivos sensibles
- Configuración de uploads securizada
- Logs estructurados para debugging
