# Guia de Implantação (Deploy) — CROM Wiki WebApp

Este documento orienta o processo de deploy e configuração do CROM Wiki WebApp na VPS Debian. O objetivo é configurar o subdomínio **`wiki.crom.run`** de forma isolada, garantindo coexistência harmônica com outros projetos ativos no mesmo servidor.

---

## 1. Requisitos do Servidor

* **Sistema Operacional**: Debian 11/12 (ou similar Linux)
* **PHP**: Versão `>= 8.1` (PHP 8.2+ recomendado)
  * Extensões necessárias: `php-cli`, `php-fpm`, `php-sqlite3`, `php-xml`, `php-mbstring`, `php-curl`, `php-zip`
* **Composer**: Gerenciador de dependências PHP instalado globalmente
* **Servidor Web**: Nginx ou Apache2
* **Banco de Dados**: SQLite3 (nativo no PHP, sem necessidade de servidores MySQL/PostgreSQL adicionais)

---

## 2. Preparação do Diretório na VPS

Acesse a VPS via SSH e navegue até a raiz de documentos padrão:
```bash
cd /var/www
```

Clone o repositório público (ou privado com chave SSH) no diretório desejado:
```bash
git clone https://github.com/seu-usuario/crom-wiki-webapp.git crom-wiki-webapp
cd crom-wiki-webapp
```

---

## 3. Instalação e Variáveis de Ambiente

1. Crie o arquivo `.env` de produção a partir do modelo:
   ```bash
   cp .env.example .env
   ```

2. Edite o arquivo `.env` ajustando os parâmetros de ambiente:
   ```bash
   nano .env
   ```
   * Defina `YII_DEBUG=false` e `YII_ENV=prod`.
   * Gere uma chave aleatória e segura de 32 caracteres para `COOKIE_VALIDATION_KEY`.

---

## 4. Instalação de Dependências e Permissões

1. Instale as dependências de produção do Composer (otimizando a velocidade e o autoload do PHP):
   ```bash
   composer install --no-dev --optimize-autoloader --no-interaction
   ```

2. Permita escrita nos diretórios exigidos pelo Yii2 e SQLite (o proprietário deve ser o usuário do servidor web, geralmente `www-data` no Debian):
   ```bash
   # Cria as pastas caso não existam
   mkdir -p runtime web/assets data backups
   
   # Ajusta o proprietário para o servidor web www-data
   chown -R www-data:www-data runtime web/assets data backups
   
   # Concede permissões adequadas de leitura, escrita e execução
   chmod -R 775 runtime web/assets data backups
   ```

---

## 5. Inicialização do Banco de Dados SQLite

Execute as migrações integradas do Yii2 para criar as tabelas do banco de dados na base SQLite de produção (`data/core.db`):
```bash
php yii migrate --interactive=0
```

Se precisar criar o usuário administrador inicial, você pode rodar o comando interativo do console do Yii2 ou importar as tabelas correspondentes. O banco SQLite será alimentado automaticamente na pasta `data/`.

---

## 6. Configuração do Servidor Web (Isolação de Projetos)

Como o servidor possui outros projetos ativos, configure o domínio **`wiki.crom.run`** para apontar de forma isolada à pasta **`/var/www/crom-wiki-webapp/web`**.

### Opção A: Nginx (Recomendado)

1. Crie um novo arquivo de bloco de servidor (Server Block) para o projeto:
   ```bash
   nano /etc/nginx/sites-available/wiki.crom.run
   ```

2. Insira a configuração abaixo, certificando-se de apontar o socket do PHP-FPM correto instalado no sistema (ex: `/run/php/php8.2-fpm.sock`):
   ```nginx
   server {
       charset utf-8;
       client_max_body_size 128M;

       listen 80;
       server_name wiki.crom.run;

       root /var/www/crom-wiki-webapp/web;
       index index.php;

       location / {
           # Redireciona requisições para arquivos inexistentes ao index.php do Yii (Pretty URLs)
           try_files $uri $uri/ /index.php?$args;
       }

       # Bloqueia acessos à pasta de runtime do Yii e arquivos ocultos do Git
       location ~ ^/(protected|framework|themes/\w+/views|runtime|backups) {
           deny all;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           # Ajuste a versão do socket do PHP-FPM conforme seu servidor Debian
           fastcgi_pass unix:/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(ht|git) {
           deny all;
       }
   }
   ```

3. Ative o site e reinicie o Nginx:
   ```bash
   ln -s /etc/nginx/sites-available/wiki.crom.run /etc/nginx/sites-enabled/
   nginx -t # Valida a sintaxe da configuração
   systemctl restart nginx
   ```

---

### Opção B: Apache2

1. Crie o arquivo de VirtualHost correspondente:
   ```bash
   nano /etc/apache2/sites-available/wiki.crom.run.conf
   ```

2. Insira a seguinte configuração:
   ```apache
   <VirtualHost *:80>
       ServerName wiki.crom.run
       DocumentRoot "/var/www/crom-wiki-webapp/web"
       
       <Directory "/var/www/crom-wiki-webapp/web">
           # Permite o uso do .htaccess local para roteamento inteligente (Pretty URLs)
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog ${APACHE_LOG_DIR}/wiki_crom_error.log
       CustomLog ${APACHE_LOG_DIR}/wiki_crom_access.log combined
   </VirtualHost>
   ```

3. Ative o VirtualHost, o módulo Rewrite e reinicie o Apache:
   ```bash
   a2ensite wiki.crom.run.conf
   a2enmod rewrite
   systemctl restart apache2
   ```

---

## 7. Configuração do HTTPS (Certificado SSL Gratuito Let's Encrypt)

Para configurar conexão HTTPS segura no subdomínio, utilize o **Certbot**:

### Para Nginx:
```bash
apt update && apt install python3-certbot-nginx -y
certbot --nginx -d wiki.crom.run
```

### Para Apache:
```bash
apt update && apt install python3-certbot-apache -y
certbot --apache -d wiki.crom.run
```
O Certbot irá gerar o certificado e alterar automaticamente o Server Block ou o VirtualHost para redirecionar de HTTP para HTTPS.

---

## 8. Scripts Úteis na VPS

### Script de Atualização (`./update.sh`)
Para atualizar o projeto de forma rápida após modificações no Git, basta rodar de dentro da pasta:
```bash
./update.sh
```
*Ele efetuará git pull, atualizará dependências do Composer, rodará as migrações, limpará caches e definirá as permissões de gravação.*

### Script de Backup e Monitoramento (`./monitor.sh`)
O monitor de serviços e recursos faz backups diários compactados do banco de dados SQLite (`data/core.db`) mantendo os últimos 7 dias na pasta `backups/`.

Para rodar automaticamente todo dia à meia-noite, configure o Cron do sistema:
1. Abra o editor do Crontab como root:
   ```bash
   crontab -e
   ```
2. Adicione a linha no final do arquivo:
   ```cron
   0 0 * * * /var/www/crom-wiki-webapp/monitor.sh > /dev/null 2>&1
   ```
