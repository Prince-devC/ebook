#!/bin/bash

################################################################################
# Script d'installation initiale - VPS Hostinger
# Projet : Virtual World Digital - Laravel + Filament
################################################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_step() { echo -e "${BLUE}==> $1${NC}"; }
print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }

echo ""
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║  Installation VPS - Virtual World Digital                ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

# Vérifier que le script est exécuté avec sudo
if [ "$EUID" -ne 0 ]; then 
    print_error "Ce script doit être exécuté avec sudo"
    exit 1
fi

################################################################################
# Configuration
################################################################################

read -p "Nom de domaine (ex: ebook.example.com) : " DOMAIN_NAME
read -p "Email pour Let's Encrypt : " SSL_EMAIL
read -p "Chemin du projet [/var/www/ebook-laravel] : " PROJECT_PATH
PROJECT_PATH=${PROJECT_PATH:-/var/www/ebook-laravel}

print_warning "Configuration :"
echo "  - Domaine : $DOMAIN_NAME"
echo "  - Email SSL : $SSL_EMAIL"
echo "  - Chemin : $PROJECT_PATH"
echo ""
read -p "Continuer ? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

################################################################################
# Mise à jour du système
################################################################################

print_step "Mise à jour du système..."
apt update && apt upgrade -y
print_success "Système mis à jour"

################################################################################
# Installation PHP 8.2
################################################################################

print_step "Installation de PHP 8.2 et extensions..."
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
  php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-mbstring \
  php8.2-zip php8.2-bcmath php8.2-intl php8.2-readline php8.2-sqlite3

php -v
print_success "PHP 8.2 installé"

################################################################################
# Installation Composer
################################################################################

print_step "Installation de Composer..."
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
composer --version
print_success "Composer installé"

################################################################################
# Installation Nginx
################################################################################

print_step "Installation de Nginx..."
apt install -y nginx
systemctl enable nginx
systemctl start nginx
print_success "Nginx installé"

################################################################################
# Installation MySQL
################################################################################

print_step "Installation de MySQL..."
apt install -y mysql-server
systemctl enable mysql
systemctl start mysql

print_warning "Configuration de MySQL..."
print_warning "Veuillez suivre les instructions pour sécuriser MySQL"
mysql_secure_installation

print_success "MySQL installé"

################################################################################
# Installation Node.js et NPM
################################################################################

print_step "Installation de Node.js 20.x..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
node -v && npm -v
print_success "Node.js installé"

################################################################################
# Installation de Git
################################################################################

print_step "Installation de Git..."
apt install -y git
git --version
print_success "Git installé"

################################################################################
# Création de la structure de répertoires
################################################################################

print_step "Création de la structure de répertoires..."
mkdir -p "$PROJECT_PATH"
mkdir -p /var/www/backups
chown -R www-data:www-data "$PROJECT_PATH"
chown -R www-data:www-data /var/www/backups
print_success "Répertoires créés"

################################################################################
# Configuration Nginx
################################################################################

print_step "Configuration de Nginx..."

cat > /etc/nginx/sites-available/ebook-laravel <<EOF
server {
    listen 80;
    listen [::]:80;
    
    server_name $DOMAIN_NAME www.$DOMAIN_NAME;
    root $PROJECT_PATH/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;

    charset utf-8;

    access_log /var/log/nginx/ebook-laravel-access.log;
    error_log /var/log/nginx/ebook-laravel-error.log;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    client_max_body_size 50M;
}
EOF

ln -sf /etc/nginx/sites-available/ebook-laravel /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

nginx -t
systemctl reload nginx

print_success "Nginx configuré"

################################################################################
# Configuration PHP
################################################################################

print_step "Configuration de PHP pour production..."

sed -i 's/expose_php = On/expose_php = Off/' /etc/php/8.2/fpm/php.ini
sed -i 's/display_errors = On/display_errors = Off/' /etc/php/8.2/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php/8.2/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 50M/' /etc/php/8.2/fpm/php.ini
sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.2/fpm/php.ini

systemctl restart php8.2-fpm
print_success "PHP configuré"

################################################################################
# Configuration du Firewall
################################################################################

print_step "Configuration du firewall UFW..."
apt install -y ufw
ufw --force enable
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw status
print_success "Firewall configuré"

################################################################################
# Installation de Certbot pour SSL
################################################################################

print_step "Installation de Certbot..."
apt install -y certbot python3-certbot-nginx

print_warning "Configuration du certificat SSL..."
certbot --nginx -d "$DOMAIN_NAME" -d "www.$DOMAIN_NAME" --non-interactive --agree-tos --email "$SSL_EMAIL" --redirect

# Test du renouvellement automatique
certbot renew --dry-run

print_success "SSL configuré"

################################################################################
# Installation de Supervisor
################################################################################

print_step "Installation de Supervisor (pour les queues)..."
apt install -y supervisor
systemctl enable supervisor
systemctl start supervisor
print_success "Supervisor installé"

################################################################################
# Configuration des logs
################################################################################

print_step "Configuration de la rotation des logs..."

cat > /etc/logrotate.d/laravel <<EOF
$PROJECT_PATH/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
EOF

print_success "Rotation des logs configurée"

################################################################################
# Récapitulatif
################################################################################

echo ""
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║  Installation terminée avec succès ! 🎉                   ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""
print_success "Prochaines étapes :"
echo ""
echo "1. Cloner votre projet :"
echo "   cd $PROJECT_PATH"
echo "   sudo -u www-data git clone https://github.com/votre-username/ebook-laravel.git ."
echo ""
echo "2. Installer les dépendances :"
echo "   sudo -u www-data composer install --no-dev --optimize-autoloader"
echo "   sudo -u www-data npm install && npm run build"
echo ""
echo "3. Configurer l'environnement :"
echo "   sudo -u www-data cp .env.example .env"
echo "   sudo nano $PROJECT_PATH/.env"
echo "   sudo -u www-data php artisan key:generate"
echo ""
echo "4. Configurer la base de données :"
echo "   # Pour SQLite :"
echo "   sudo -u www-data touch $PROJECT_PATH/database/database.sqlite"
echo "   sudo chmod 664 $PROJECT_PATH/database/database.sqlite"
echo "   "
echo "   # Ou pour MySQL :"
echo "   sudo mysql"
echo "   CREATE DATABASE ebook_laravel;"
echo "   CREATE USER 'ebook_user'@'localhost' IDENTIFIED BY 'password';"
echo "   GRANT ALL PRIVILEGES ON ebook_laravel.* TO 'ebook_user'@'localhost';"
echo ""
echo "5. Migrer et initialiser :"
echo "   sudo -u www-data php artisan migrate:fresh --seed --force"
echo "   sudo -u www-data php artisan storage:link"
echo "   sudo -u www-data php artisan optimize"
echo ""
echo "6. Accéder à l'application :"
echo "   Site : https://$DOMAIN_NAME"
echo "   Admin : https://$DOMAIN_NAME/admin"
echo "   (admin@ebook.com / password)"
echo ""
print_warning "N'oubliez pas de changer le mot de passe admin !"
echo ""
