#!/usr/bin/env bash

# Script de Monitoramento e Backup CROM (SRE-Engine)
# Uso: ./monitor.sh

# Cores para output
GREEN='\033[0;32m'
NC='\033[0m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'

# Diretório base
BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKUP_DIR="${BASE_DIR}/backups"
DB_FILE="${BASE_DIR}/data/core.db"

echo -e "${BLUE}=== Sistema de Monitoramento & Backup CROM ===${NC}"

# ==========================================
# 1. ROTINA DE BACKUP DO BANCO SQLITE
# ==========================================
echo -e "\n${BLUE}[1] Iniciando rotina de Backup do Banco SQLite...${NC}"
mkdir -p "${BACKUP_DIR}"

if [ -f "${DB_FILE}" ]; then
    TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
    BACKUP_FILE="${BACKUP_DIR}/core_backup_${TIMESTAMP}.sqlite.gz"
    
    # Faz cópia segura usando a ferramenta sqlite3 para evitar corrupções em escrita
    if command -v sqlite3 >/dev/null 2>&1; then
        sqlite3 "${DB_FILE}" ".backup '${BACKUP_DIR}/temp_core_${TIMESTAMP}.db'"
        gzip -c "${BACKUP_DIR}/temp_core_${TIMESTAMP}.db" > "${BACKUP_FILE}"
        rm -f "${BACKUP_DIR}/temp_core_${TIMESTAMP}.db"
    else
        # Fallback simples caso sqlite3 não esteja instalado
        gzip -c "${DB_FILE}" > "${BACKUP_FILE}"
    fi
    
    # Mantém apenas os últimos 7 backups para economizar disco
    find "${BACKUP_DIR}" -name "core_backup_*.sqlite.gz" -mtime +7 -delete
    
    echo -e "${GREEN}Backup realizado com sucesso: ${BACKUP_FILE}${NC}"
else
    echo -e "${RED}Erro: Arquivo do banco de dados não encontrado em ${DB_FILE}${NC}"
fi

# ==========================================
# 2. MONITORAMENTO DE RECURSOS DA VPS
# ==========================================
echo -e "\n${BLUE}[2] Status de Recursos do Sistema:${NC}"

# Espaço em disco
DISK_USAGE=$(df -h "${BASE_DIR}" | tail -n 1 | awk '{print $5}')
echo -e "Uso de Disco (${BASE_DIR}): ${YELLOW}${DISK_USAGE}${NC}"

# Memória RAM
if command -v free >/dev/null 2>&1; then
    MEM_USAGE=$(free -h | awk '/Mem:/ {print $3 "/" $2}')
    echo -e "Uso de Memória RAM: ${YELLOW}${MEM_USAGE}${NC}"
fi

# ==========================================
# 3. VERIFICAÇÃO DE SERVIÇOS CRÍTICOS
# ==========================================
echo -e "\n${BLUE}[3] Status dos Serviços do Servidor Web:${NC}"

check_service() {
    local service=$1
    if systemctl is-active --quiet "${service}" 2>/dev/null; then
        echo -e "Serviço ${GREEN}${service}${NC}: ${GREEN}Ativo (Running)${NC}"
    else
        echo -e "Serviço ${RED}${service}${NC}: ${RED}Inativo ou Não Instalado${NC}"
    fi
}

# Verifica servidores web comuns e versões do PHP-FPM
if command -v systemctl >/dev/null 2>&1; then
    # Verifica Nginx
    if systemctl list-unit-files | grep -q nginx; then
        check_service "nginx"
    fi
    
    # Verifica Apache2
    if systemctl list-unit-files | grep -q apache2; then
        check_service "apache2"
    fi

    # Verifica PHP-FPM (procura por qualquer versão instalada do PHP-FPM)
    PHP_FPM_SERVICE=$(systemctl list-units --type=service --all | grep -o 'php[0-9.]\+-fpm' | head -n 1)
    if [ -n "${PHP_FPM_SERVICE}" ]; then
        check_service "${PHP_FPM_SERVICE}"
    else
        echo -e "PHP-FPM: ${YELLOW}Não detectado via systemctl${NC}"
    fi
else
    echo -e "${YELLOW}systemctl não disponível para checar status dos serviços.${NC}"
fi

echo -e "\n${GREEN}=== Monitoramento concluído! ===${NC}"
