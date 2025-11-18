#!/bin/bash

################################################################################
# Script de déploiement automatique - VPS Hostinger
# Projet : Virtual World Digital - Laravel + Filament
################################################################################

set -e  # Arrêter en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_PATH="/var/www/ebook-laravel"
BACKUP_PATH="/var/www/backups"
DATE=$(date +%Y%m%d_%H%M%S)

################################################################################
# Fonctions utilitaires
################################################################################

print_step() {
    echo -e "${BLUE}==> $1${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

################################################################################
# Vérifications préliminaires
################################################################################

check_requirements() {
    print_step "Vérification des prérequis..."
    
    # Vérifier que le script est exécuté avec les bons privilèges
    if [ "$EUID" -ne 0 ]; then 
        print_error "Ce script doit être exécuté avec sudo"
        exit 1
    fi
    
    # Vérifier que le répertoire du projet existe
    if [ ! -d "$PROJECT_PATH" ]; then
        print_error "Le répertoire du projet n'existe pas : $PROJECT_PATH"
        exit 1
    fi
    
    print_success "Prérequis OK"
}

################################################################################
# Backup
################################################################################

create_backup() {
    print_step "Création du backup..."
    
    # Créer le dossier de backup s'il n'existe pas
    mkdir -p "$BACKUP_PATH"
    
    # Backup de la base de données SQLite
    if [ -f "$PROJECT_PATH/database/database.sqlite" ]; then
        cp "$PROJECT_PATH/database/database.sqlite" "$BACKUP_PATH/database_$DATE.sqlite"
        print_success "Backup SQLite créé : database_$DATE.sqlite"
    fi
    
    # Backup de la base MySQL (si utilisé)
    if grep -q "DB_CONNECTION=mysql" "$PROJECT_PATH/.env"; then
        DB_NAME=$(grep DB_DATABASE "$PROJECT_PATH/.env" | cut -d '=' -f2)
        DB_USER=$(grep DB_USERNAME "$PROJECT_PATH/.env" | cut -d '=' -f2)
        DB_PASS=$(grep DB_PASSWORD "$PROJECT_PATH/.env" | cut -d '=' -f2)
        
        if [ ! -z "$DB_NAME" ]; then
            mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_PATH/mysql_$DATE.sql"
            print_success "Backup MySQL créé : mysql_$DATE.sql"
        fi
    fi
    
    # Backup du fichier .env
    cp "$PROJECT_PATH/.env" "$BACKUP_PATH/.env_$DATE"
    print_success "Backup .env créé"
    
    # Nettoyer les anciens backups (garder les 7 derniers)
    ls -t "$BACKUP_PATH"/database_*.sqlite 2>/dev/null | tail -n +8 | xargs -r rm --
    ls -t "$BACKUP_PATH"/mysql_*.sql 2>/dev/null | tail -n +8 | xargs -r rm --
    ls -t "$BACKUP_PATH"/.env_* 2>/dev/null | tail -n +8 | xargs -r rm --
    
    print_success "Backup terminé"
}

################################################################################
# Déploiement
################################################################################

deploy() {
    print_step "Début du déploiement..."
    
    cd "$PROJECT_PATH"
    
    # Mode maintenance
    print_step "Activation du mode maintenance..."
    sudo -u www-data php artisan down --render="errors::503" --secret="deploiement-2024"
    print_warning "Mode maintenance activé. Accès via : /deploiement-2024"
    
    # Mise à jour du code depuis Git
    print_step "Récupération du code depuis Git..."
    if [ -d "$PROJECT_PATH/.git" ]; then
        sudo -u www-data git fetch origin
        CURRENT_BRANCH=$(sudo -u www-data git rev-parse --abbrev-ref HEAD)
        print_step "Branche actuelle : $CURRENT_BRANCH"
        sudo -u www-data git pull origin "$CURRENT_BRANCH"
        print_success "Code mis à jour"
    else
        print_warning "Pas de repository Git détecté, passage à l'étape suivante"
    fi
    
    # Installation des dépendances Composer
    print_step "Installation des dépendances Composer..."
    sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction
    print_success "Dépendances Composer installées"
    
    # Installation des dépendances NPM et compilation des assets
    print_step "Installation des dépendances NPM..."
    sudo -u www-data npm install --silent
    print_success "Dépendances NPM installées"
    
    print_step "Compilation des assets Vite..."
    sudo -u www-data npm run build
    print_success "Assets compilés"
    
    # Migrations de base de données
    print_step "Exécution des migrations..."
    sudo -u www-data php artisan migrate --force
    print_success "Migrations exécutées"
    
    # Nettoyage du cache
    print_step "Nettoyage du cache..."
    sudo -u www-data php artisan cache:clear
    sudo -u www-data php artisan config:clear
    sudo -u www-data php artisan route:clear
    sudo -u www-data php artisan view:clear
    print_success "Cache nettoyé"
    
    # Optimisation
    print_step "Optimisation de l'application..."
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    sudo -u www-data php artisan event:cache
    sudo -u www-data php artisan optimize
    print_success "Application optimisée"
    
    # Vérifier le lien symbolique storage
    if [ ! -L "$PROJECT_PATH/public/storage" ]; then
        print_step "Création du lien symbolique storage..."
        sudo -u www-data php artisan storage:link
        print_success "Lien symbolique créé"
    fi
    
    # Permissions
    print_step "Vérification des permissions..."
    chown -R www-data:www-data "$PROJECT_PATH"
    chmod -R 755 "$PROJECT_PATH"
    chmod -R 775 "$PROJECT_PATH/storage"
    chmod -R 775 "$PROJECT_PATH/bootstrap/cache"
    
    if [ -f "$PROJECT_PATH/database/database.sqlite" ]; then
        chmod 664 "$PROJECT_PATH/database/database.sqlite"
        chmod 775 "$PROJECT_PATH/database"
    fi
    print_success "Permissions configurées"
    
    # Redémarrage des services
    print_step "Redémarrage des services..."
    systemctl reload nginx
    systemctl restart php8.2-fpm
    print_success "Services redémarrés"
    
    # Désactivation du mode maintenance
    print_step "Désactivation du mode maintenance..."
    sudo -u www-data php artisan up
    print_success "Mode maintenance désactivé"
    
    print_success "Déploiement terminé avec succès ! 🎉"
}

################################################################################
# Vérifications post-déploiement
################################################################################

post_deploy_checks() {
    print_step "Vérifications post-déploiement..."
    
    # Vérifier que Nginx tourne
    if systemctl is-active --quiet nginx; then
        print_success "Nginx est actif"
    else
        print_error "Nginx n'est pas actif !"
    fi
    
    # Vérifier que PHP-FPM tourne
    if systemctl is-active --quiet php8.2-fpm; then
        print_success "PHP-FPM est actif"
    else
        print_error "PHP-FPM n'est pas actif !"
    fi
    
    # Vérifier la configuration Nginx
    if nginx -t 2>/dev/null; then
        print_success "Configuration Nginx valide"
    else
        print_error "Configuration Nginx invalide !"
    fi
    
    # Afficher les dernières lignes du log Laravel
    if [ -f "$PROJECT_PATH/storage/logs/laravel.log" ]; then
        print_step "Dernières lignes du log Laravel :"
        tail -n 5 "$PROJECT_PATH/storage/logs/laravel.log"
    fi
    
    print_success "Vérifications terminées"
}

################################################################################
# Fonction de rollback
################################################################################

rollback() {
    print_warning "Rollback en cours..."
    
    # Restaurer le dernier backup de la base de données
    LATEST_SQLITE_BACKUP=$(ls -t "$BACKUP_PATH"/database_*.sqlite 2>/dev/null | head -n 1)
    if [ ! -z "$LATEST_SQLITE_BACKUP" ]; then
        cp "$LATEST_SQLITE_BACKUP" "$PROJECT_PATH/database/database.sqlite"
        print_success "Base de données restaurée depuis : $LATEST_SQLITE_BACKUP"
    fi
    
    # Restaurer le fichier .env
    LATEST_ENV_BACKUP=$(ls -t "$BACKUP_PATH"/.env_* 2>/dev/null | head -n 1)
    if [ ! -z "$LATEST_ENV_BACKUP" ]; then
        cp "$LATEST_ENV_BACKUP" "$PROJECT_PATH/.env"
        print_success "Fichier .env restauré"
    fi
    
    # Revenir au commit précédent (si Git)
    if [ -d "$PROJECT_PATH/.git" ]; then
        cd "$PROJECT_PATH"
        sudo -u www-data git reset --hard HEAD~1
        print_success "Code restauré au commit précédent"
    fi
    
    # Redéployer
    deploy
    
    print_success "Rollback terminé"
}

################################################################################
# Menu principal
################################################################################

show_menu() {
    echo ""
    echo "╔═══════════════════════════════════════════════════════════╗"
    echo "║  Script de Déploiement - Virtual World Digital          ║"
    echo "╚═══════════════════════════════════════════════════════════╝"
    echo ""
    echo "1) Déploiement complet (avec backup)"
    echo "2) Déploiement sans backup"
    echo "3) Backup uniquement"
    echo "4) Rollback (restaurer dernier backup)"
    echo "5) Vérifications post-déploiement"
    echo "6) Quitter"
    echo ""
    read -p "Choisissez une option [1-6] : " choice
    
    case $choice in
        1)
            check_requirements
            create_backup
            deploy
            post_deploy_checks
            ;;
        2)
            check_requirements
            deploy
            post_deploy_checks
            ;;
        3)
            check_requirements
            create_backup
            ;;
        4)
            check_requirements
            rollback
            ;;
        5)
            post_deploy_checks
            ;;
        6)
            echo "Au revoir !"
            exit 0
            ;;
        *)
            print_error "Option invalide"
            show_menu
            ;;
    esac
}

################################################################################
# Point d'entrée
################################################################################

# Si aucun argument, afficher le menu
if [ $# -eq 0 ]; then
    show_menu
else
    # Sinon, exécuter la commande directement
    case "$1" in
        deploy)
            check_requirements
            create_backup
            deploy
            post_deploy_checks
            ;;
        backup)
            check_requirements
            create_backup
            ;;
        rollback)
            check_requirements
            rollback
            ;;
        check)
            post_deploy_checks
            ;;
        *)
            echo "Usage: $0 {deploy|backup|rollback|check}"
            echo "  ou exécutez sans argument pour le menu interactif"
            exit 1
            ;;
    esac
fi

echo ""
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║  Déploiement terminé - $(date +"%Y-%m-%d %H:%M:%S")      ║"
echo "╚═══════════════════════════════════════════════════════════╝"
