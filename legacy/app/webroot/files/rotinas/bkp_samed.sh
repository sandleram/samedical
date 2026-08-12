#!/bin/bash

# Defina as variáveis do banco de dados
DB_HOST="localhost"
DB_USER="desenvol_admin"
DB_PASS="pnl8000"
DB_NAME="desenvol_uotk"

# Defina o diretório de destino para o backup
BACKUP_DIR="/home/desenvol/public_html/bkp_db/samed"

# Defina o nome do arquivo de backup
BACKUP_FILE="$BACKUP_DIR/backup_$(date +%Y-%m-%d_%H-%M-%S).sql"

# Crie o diretório de backup, se não existir
mkdir -p $BACKUP_DIR

# Faça o backup do banco de dados usando mysqldump
mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE

# Verifique se o backup foi criado com sucesso
if [ $? -eq 0 ]; then
    echo "Backup do banco de dados foi criado com sucesso em: $BACKUP_FILE"
else
    echo "Erro ao criar o backup do banco de dados"
fi
