#!/usr/bin/env bash

# Script de Atualização Automatizada CROM (SRE-Engine)
# Uso: ./update.sh

set -e # Aborta o script em caso de erros

# Cores para output
GREEN='\033[0;32m'
NC='\033[0m'
BLUE='\033[0;34m'
RED='\033[0;31m'

echo -e "${BLUE}=== Iniciando Atualização do CROM Wiki WebApp ===${NC}"

# 1. Puxar modificações do repositório Git
echo -e "${BLUE}[1/5] Executando Git Pull...${NC}"
git pull

# 2. Instalar dependências de produção do Composer
echo -e "${BLUE}[2/5] Atualizando dependências PHP via Composer...${NC}"
if [ -f "composer.phar" ]; then
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
else
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 3. Executar as migrações do banco de dados SQLite
echo -e "${BLUE}[3/5] Aplicando migrações do Yii2...${NC}"
php yii migrate --interactive=0

# 4. Limpar caches e assets temporários
echo -e "${BLUE}[4/5] Limpando caches e assets temporários...${NC}"
php yii cache/flush-all --interactive=0 || true

# Limpa a pasta web/assets, mas mantém o .gitignore
echo -e "Limpando ativos do frontend web..."
find web/assets -mindepth 1 -maxdepth 1 ! -name '.gitignore' -exec rm -rf {} +

# 5. Ajustar permissões de arquivos para o servidor web www-data
echo -e "${BLUE}[5/5] Ajustando permissões dos diretórios graváveis...${NC}"
# Garante que as pastas de runtime, assets e data tenham permissões de escrita
chmod -R 775 runtime web/assets data
# Se o script estiver rodando como root, ajusta o proprietário para o Apache/Nginx (www-data)
if [ "$(id -u)" -eq 0 ]; then
    chown -R www-data:www-data runtime web/assets data
    echo -e "Propriedade dos diretórios atualizada para www-data."
fi

echo -e "${GREEN}=== Atualização concluída com sucesso! ===${NC}"
