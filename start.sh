#!/bin/bash

# Script para inicializar el proyecto Sistema Escolar con Docker
# Uso: ./start.sh

echo "🏫 Iniciando Sistema Escolar con Docker..."

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker no está instalado. Por favor, instala Docker primero."
    exit 1
fi

# Verificar si Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Error: Docker Compose no está instalado. Por favor, instala Docker Compose primero."
    exit 1
fi

# Parar contenedores existentes (si los hay)
echo "🛑 Parando contenedores existentes..."
docker-compose down

# Construir y levantar contenedores
echo "🏗️  Construyendo y levantando contenedores..."
docker-compose up -d --build

# Esperar a que los servicios estén listos
echo "⏳ Esperando a que los servicios estén listos..."
sleep 10

# Verificar el estado de los contenedores
echo "📊 Estado de los contenedores:"
docker-compose ps

echo ""
echo "✅ ¡Sistema Escolar está listo!"
echo ""
echo "🌐 Accede a tu aplicación en:"
echo "   - Aplicación web: http://localhost:8080"
echo "   - phpMyAdmin: http://localhost:8081"
echo ""
echo "🔐 Credenciales de la base de datos:"
echo "   - Usuario: root"
echo "   - Contraseña: rootpassword"
echo "   - Base de datos: mi_proyecto"
echo ""
echo "📝 Para ver los logs: docker-compose logs"
echo "🛑 Para parar: docker-compose down"
echo ""
