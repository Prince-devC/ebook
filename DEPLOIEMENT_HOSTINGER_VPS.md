# 🚀 Guide de Déploiement - VPS Hostinger

## 📋 Table des matières
1. [Prérequis](#prérequis)
2. [Préparation du VPS](#préparation-du-vps)
3. [Déploiement du projet](#déploiement-du-projet)
4. [Configuration Nginx](#configuration-nginx)
5. [Configuration SSL](#configuration-ssl)
6. [Optimisation et sécurité](#optimisation-et-sécurité)
7. [Maintenance](#maintenance)

---

## 🎯 Prérequis

### Sur le VPS Hostinger

Votre VPS doit avoir :
- ✅ Ubuntu 20.04+ / Debian 11+
- ✅ Accès SSH root ou sudo
- ✅ Domaine pointant vers l'IP du VPS

### Vérification des services installés

```bash
# Connexion SSH
ssh payix@srv1073422.hstgr.cloud
# ou
ssh payix@votre-ip

# Vérifier les services installés
php -v          # PHP version
nginx -v        # ou apache2 -v
mysql --version
composer --version
git --version
```

---

## 🛠️ Préparation du VPS

### 1. Mise à jour du système

```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Installation des prérequis (si manquants)

#### PHP 8.2+ et extensions requises

```bash
# Ajouter le repository PHP
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Installer PHP 8.2 et extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
  php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-mbstring \
  php8.2-zip php8.2-bcmath php8.2-intl php8.2-readline php8.2-sqlite3

# Vérifier
php -v
```

#### Composer

```bash
# Si Composer n'est pas installé
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Vérifier
composer --version
```

#### Nginx (serveur web)

```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

#### MySQL

```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# Sécuriser MySQL
sudo mysql_secure_installation
```

#### Git

```bash
sudo apt install git -y
```

#### Node.js & NPM (pour Vite)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v && npm -v
```

### 3. Configuration de la structure des répertoires

```bash
# Votre structure actuelle
cd /var/www
ls -la
# Vous voyez : html/ et payix/

# On va créer un nouveau dossier pour le projet ebook
sudo mkdir -p /var/www/ebook-laravel
sudo chown -R www-data:www-data /var/www/ebook-laravel
sudo chmod -R 755 /var/www/ebook-laravel
```

---

## 📦 Déploiement du Projet

### Option A : Déploiement via Git (Recommandé)

#### 1. Créer un repository GitHub (si pas fait)

Sur votre machine locale :

```bash
cd /Applications/MAMP/htdocs/ebook-laravel

# Initialiser Git si nécessaire
git init
git add .
git commit -m "Prêt pour déploiement VPS Hostinger"

# Créer un repo sur GitHub puis :
git remote add origin https://github.com/votre-username/ebook-laravel.git
git branch -M main
git push -u origin main
```

#### 2. Cloner sur le VPS

```bash
# Sur le VPS
cd /var/www/ebook-laravel

# Cloner le projet
sudo -u www-data git clone https://github.com/votre-username/ebook-laravel.git .

# Si repository privé, utiliser token ou SSH key
```

### Option B : Transfert via SFTP/SCP

Sur votre machine locale :

```bash
# Compresser le projet (exclure node_modules et vendor)
cd /Applications/MAMP/htdocs
tar -czf ebook-laravel.tar.gz \
  --exclude='node_modules' \
  --exclude='vendor' \
  --exclude='.git' \
  --exclude='storage/logs/*' \
  --exclude='database/database.sqlite' \
  ebook-laravel/

# Transférer vers le VPS
scp ebook-laravel.tar.gz payix@srv1073422.hstgr.cloud:/tmp/

# Sur le VPS
cd /var/www/ebook-laravel
sudo tar -xzf /tmp/ebook-laravel.tar.gz --strip-components=1
sudo chown -R www-data:www-data /var/www/ebook-laravel
rm /tmp/ebook-laravel.tar.gz
```

### 3. Installation des dépendances

```bash
cd /var/www/ebook-laravel

# Installer les dépendances PHP (production)
sudo -u www-data composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js
sudo -u www-data npm install

# Compiler les assets
sudo -u www-data npm run build
```

### 4. Configuration de l'environnement

```bash
# Copier le fichier .env
sudo -u www-data cp .env.example .env

# Éditer le fichier .env
sudo nano .env
```

**Configuration .env pour production :**

```env
APP_NAME="Virtual World Digital"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://votre-domaine.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

LOG_CHANNEL=daily
LOG_LEVEL=error

# Option 1 : SQLite (Recommandé pour petits projets)
DB_CONNECTION=sqlite

# Option 2 : MySQL (Recommandé pour production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ebook_laravel
# DB_USERNAME=ebook_user
# DB_PASSWORD=VotreMotDePasseSecurise123!

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=noreply@votre-domaine.com
MAIL_PASSWORD=VotreMotDePasseEmail
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@votre-domaine.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Sauvegarder : `Ctrl+O` puis `Ctrl+X`

### 5. Configuration de la base de données

#### Option A : SQLite (Simple)

```bash
# Créer le fichier SQLite
sudo -u www-data touch /var/www/ebook-laravel/database/database.sqlite
sudo chmod 664 /var/www/ebook-laravel/database/database.sqlite
```

#### Option B : MySQL (Production)

```bash
# Se connecter à MySQL
sudo mysql

# Dans MySQL :
CREATE DATABASE ebook_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ebook_user'@'localhost' IDENTIFIED BY 'VotreMotDePasseSecurise123!';
GRANT ALL PRIVILEGES ON ebook_laravel.* TO 'ebook_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Générer la clé et migrer

```bash
cd /var/www/ebook-laravel

# Générer la clé d'application
sudo -u www-data php artisan key:generate

# Créer le lien symbolique pour le storage
sudo -u www-data php artisan storage:link

# Exécuter les migrations et seeders
sudo -u www-data php artisan migrate:fresh --seed --force

# ⚠️ Note : --force est nécessaire en production
# Cela crée les catégories, ebooks et l'utilisateur admin
```

### 7. Configurer les permissions

```bash
# Permissions correctes
sudo chown -R www-data:www-data /var/www/ebook-laravel
sudo chmod -R 755 /var/www/ebook-laravel
sudo chmod -R 775 /var/www/ebook-laravel/storage
sudo chmod -R 775 /var/www/ebook-laravel/bootstrap/cache

# Si SQLite
sudo chmod 664 /var/www/ebook-laravel/database/database.sqlite
sudo chmod 775 /var/www/ebook-laravel/database
```

### 8. Optimisation pour production

```bash
cd /var/www/ebook-laravel

# Mettre en cache les configurations
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache

# Optimisation générale
sudo -u www-data php artisan optimize
```

---

## 🌐 Configuration Nginx

### 1. Créer le fichier de configuration

```bash
sudo nano /etc/nginx/sites-available/ebook-laravel
```

**Contenu du fichier :**

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name votre-domaine.com www.votre-domaine.com;
    root /var/www/ebook-laravel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;

    charset utf-8;

    # Logs
    access_log /var/log/nginx/ebook-laravel-access.log;
    error_log /var/log/nginx/ebook-laravel-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Bloquer l'accès aux fichiers sensibles
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    # Gestion du cache pour les assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Limite de taille d'upload (pour les PDF et images)
    client_max_body_size 50M;
}
```

Sauvegarder : `Ctrl+O` puis `Ctrl+X`

### 2. Activer le site

```bash
# Créer le lien symbolique
sudo ln -s /etc/nginx/sites-available/ebook-laravel /etc/nginx/sites-enabled/

# Si vous voulez désactiver le site par défaut
sudo rm /etc/nginx/sites-enabled/default

# Tester la configuration
sudo nginx -t

# Si OK, recharger Nginx
sudo systemctl reload nginx
```

### 3. Vérifier PHP-FPM

```bash
# S'assurer que PHP-FPM tourne
sudo systemctl status php8.2-fpm
sudo systemctl enable php8.2-fpm
sudo systemctl start php8.2-fpm
```

---

## 🔒 Configuration SSL (HTTPS)

### Installation de Certbot (Let's Encrypt)

```bash
# Installer Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtenir un certificat SSL
sudo certbot --nginx -d virtualworlddigital.com -d www.virtualworlddigital.com

# Suivre les instructions :
# - Entrez votre email
# - Acceptez les conditions
# - Choisissez de rediriger HTTP vers HTTPS (recommandé : option 2)

# Tester le renouvellement automatique
sudo certbot renew --dry-run
```

**Certbot modifiera automatiquement votre config Nginx pour ajouter HTTPS.**

### Vérification

```bash
# Votre site devrait maintenant être accessible en HTTPS
curl -I https://votre-domaine.com
```

---

## 🔐 Optimisation et Sécurité

### 1. Configurer le Firewall

```bash
# Installer UFW si pas déjà fait
sudo apt install ufw -y

# Autoriser SSH, HTTP, HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'

# Activer le firewall
sudo ufw enable
sudo ufw status
```

### 2. Changer le mot de passe admin

```bash
cd /var/www/ebook-laravel

# Ouvrir Tinker
sudo -u www-data php artisan tinker
```

Dans Tinker :

```php
$user = App\Models\User::where('email', 'admin@ebook.com')->first();
$user->password = bcrypt('VotreNouveauMotDePasseSecurise123!');
$user->save();
exit;
```

### 3. Protéger les fichiers sensibles

Déjà fait via Nginx, mais vérifiez :

```bash
# Ces fichiers ne doivent PAS être accessibles
curl https://votre-domaine.com/.env
curl https://votre-domaine.com/composer.json
# Doivent retourner 403 Forbidden
```

### 4. Configuration PHP pour production

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Modifiez :

```ini
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php8.2-fpm.log
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

Redémarrer PHP-FPM :

```bash
sudo systemctl restart php8.2-fpm
```

### 5. Configurer les logs Laravel

```bash
# Créer une rotation des logs
sudo nano /etc/logrotate.d/laravel
```

Contenu :

```
/var/www/ebook-laravel/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 6. Configuration du Queue Worker (optionnel)

Si vous utilisez les queues :

```bash
# Créer un service systemd
sudo nano /etc/systemd/system/ebook-worker.service
```

Contenu :

```ini
[Unit]
Description=Ebook Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/ebook-laravel/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

Activer :

```bash
sudo systemctl enable ebook-worker
sudo systemctl start ebook-worker
sudo systemctl status ebook-worker
```

### 7. Configurer un Cron pour le scheduler

```bash
sudo crontab -e -u www-data
```

Ajouter :

```
* * * * * cd /var/www/ebook-laravel && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚀 Accès à l'Application

### Site Public
```
https://votre-domaine.com
```

Fonctionnalités :
- Catalogue d'ebooks
- Système de panier
- Processus de commande

### Panel Admin Filament
```
https://votre-domaine.com/admin
```

**Identifiants (à changer immédiatement) :**
- Email : `admin@ebook.com`
- Mot de passe : celui que vous avez défini avec Tinker

---

## 🔄 Maintenance et Mises à Jour

### Mettre à jour le code

```bash
cd /var/www/ebook-laravel

# Sauvegarder la base de données (SQLite)
sudo cp database/database.sqlite database/database.sqlite.backup

# Ou pour MySQL
# sudo mysqldump -u ebook_user -p ebook_laravel > /tmp/backup.sql

# Récupérer les dernières modifications
sudo -u www-data git pull origin main

# Mettre à jour les dépendances
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm install
sudo -u www-data npm run build

# Exécuter les migrations
sudo -u www-data php artisan migrate --force

# Vider le cache
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan optimize

# Redémarrer les services
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm
```

### Script de déploiement automatique

Créez un script pour automatiser :

```bash
sudo nano /var/www/ebook-laravel/deploy.sh
```

Contenu :

```bash
#!/bin/bash

set -e

echo "🚀 Début du déploiement..."

cd /var/www/ebook-laravel

# Mode maintenance
sudo -u www-data php artisan down

# Mise à jour du code
echo "📥 Récupération du code..."
sudo -u www-data git pull origin main

# Dépendances
echo "📦 Installation des dépendances..."
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm install
sudo -u www-data npm run build

# Base de données
echo "🗄️ Migration de la base de données..."
sudo -u www-data php artisan migrate --force

# Cache
echo "🧹 Nettoyage et optimisation..."
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan optimize

# Fin du mode maintenance
sudo -u www-data php artisan up

# Redémarrage
echo "🔄 Redémarrage des services..."
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm

echo "✅ Déploiement terminé avec succès !"
```

Rendre exécutable :

```bash
sudo chmod +x /var/www/ebook-laravel/deploy.sh
```

Utilisation :

```bash
sudo /var/www/ebook-laravel/deploy.sh
```

---

## 🐛 Dépannage

### Erreur 500

```bash
# Vérifier les logs Laravel
sudo tail -f /var/www/ebook-laravel/storage/logs/laravel.log

# Vérifier les logs Nginx
sudo tail -f /var/log/nginx/ebook-laravel-error.log

# Vérifier les logs PHP
sudo tail -f /var/log/php8.2-fpm.log
```

### Erreur de permissions

```bash
sudo chown -R www-data:www-data /var/www/ebook-laravel
sudo chmod -R 755 /var/www/ebook-laravel
sudo chmod -R 775 /var/www/ebook-laravel/storage
sudo chmod -R 775 /var/www/ebook-laravel/bootstrap/cache
```

### Problème de cache

```bash
cd /var/www/ebook-laravel
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan optimize:clear
```

### Base de données verrouillée (SQLite)

```bash
# Vérifier les processus qui utilisent la DB
sudo lsof /var/www/ebook-laravel/database/database.sqlite

# Redémarrer PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Site non accessible

```bash
# Vérifier Nginx
sudo systemctl status nginx
sudo nginx -t

# Vérifier PHP-FPM
sudo systemctl status php8.2-fpm

# Vérifier les ports
sudo netstat -tlnp | grep :80
sudo netstat -tlnp | grep :443

# Vérifier le firewall
sudo ufw status
```

---

## 📊 Monitoring

### Installation de Supervisor (pour surveiller les workers)

```bash
sudo apt install supervisor -y
```

### Créer une configuration

```bash
sudo nano /etc/supervisor/conf.d/ebook-worker.conf
```

Contenu :

```ini
[program:ebook-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ebook-laravel/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ebook-laravel/storage/logs/worker.log
stopwaitsecs=3600
```

Activer :

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ebook-worker:*
```

---

## 📝 Checklist Finale

Avant de mettre en production, vérifiez :

- [ ] ✅ PHP 8.2+ installé avec toutes les extensions
- [ ] ✅ Nginx configuré et fonctionnel
- [ ] ✅ Base de données créée et migrée
- [ ] ✅ Fichier `.env` configuré pour production
- [ ] ✅ `APP_DEBUG=false` dans `.env`
- [ ] ✅ SSL/HTTPS activé avec Let's Encrypt
- [ ] ✅ Firewall configuré (ports 80, 443, 22)
- [ ] ✅ Permissions correctes (www-data:www-data)
- [ ] ✅ Mot de passe admin changé
- [ ] ✅ Configuration email SMTP testée
- [ ] ✅ Cache optimisé (`config:cache`, `route:cache`, etc.)
- [ ] ✅ Storage link créé
- [ ] ✅ Logs Laravel accessibles et rotatés
- [ ] ✅ Backup de la base de données configuré
- [ ] ✅ Script de déploiement créé
- [ ] ✅ Site accessible via le domaine
- [ ] ✅ Panel admin accessible et fonctionnel

---

## 🎉 Félicitations !

Votre marketplace **Virtual World Digital** est maintenant déployée sur votre VPS Hostinger !

**URLs importantes :**
- Site public : `https://votre-domaine.com`
- Admin Filament : `https://votre-domaine.com/admin`

**Prochaines étapes :**
1. Ajouter vos ebooks depuis le panel admin
2. Personnaliser les catégories
3. Configurer les méthodes de paiement réelles
4. Configurer les emails transactionnels
5. Mettre en place un système de backup automatique

---

## 📞 Support

En cas de problème :
1. Consultez les logs Laravel et Nginx
2. Vérifiez la configuration PHP et Nginx
3. Assurez-vous que tous les services tournent
4. Contactez le support Hostinger si problème serveur

---

**Développé avec ❤️ - Virtual World Digital**
