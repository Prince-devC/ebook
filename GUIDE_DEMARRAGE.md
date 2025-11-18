# 🚀 Guide de démarrage - Ebook Laravel

## Bienvenue !

Ce guide vous aidera à démarrer rapidement avec votre plateforme de vente d'ebooks.

---

## 📋 Prérequis

- PHP 8.2+
- Composer
- Base de données SQLite (déjà configurée)
- Serveur web (MAMP, XAMPP, ou `php artisan serve`)

---

## ⚡ Installation rapide

### 1. Cloner le projet (si nécessaire)

```bash
cd /Applications/MAMP/htdocs
git clone [url-du-repo] ebook-laravel
cd ebook-laravel
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

```bash
# Copier le fichier .env (si pas déjà fait)
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer la base de données

La base de données SQLite est déjà configurée. Si vous devez la recréer :

```bash
# Créer la base de données
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# Peupler avec des données de test
php artisan db:seed
```

### 5. Créer le lien symbolique pour le storage

```bash
php artisan storage:link
```

### 6. Configurer Paydunya

Éditez le fichier `.env` et ajoutez vos clés Paydunya :

```env
PAYDUNYA_MASTER_KEY=votre_master_key
PAYDUNYA_PRIVATE_KEY=votre_private_key
PAYDUNYA_TOKEN=votre_token
PAYDUNYA_MODE=test
```

> Pour obtenir vos clés : https://paydunya.com

### 7. Démarrer le serveur

```bash
php artisan serve
```

Le site sera accessible sur : **http://localhost:8000**

---

## 🎯 Accès aux interfaces

### Site public
**URL :** http://localhost:8000

Fonctionnalités :
- Catalogue d'ebooks
- Panier d'achat
- Paiement via Paydunya
- Téléchargement après achat

### Dashboard Admin
**URL :** http://localhost:8000/secureadmin

Fonctionnalités :
- Statistiques
- Gestion des ebooks
- Gestion des catégories
- Gestion des commandes

> ⚠️ **Important** : Le dashboard n'a pas d'authentification par défaut. Voir la section "Sécurité" ci-dessous.

---

## 📚 Premiers pas

### 1. Ajouter une catégorie

1. Accédez à http://localhost:8000/secureadmin/categories
2. Cliquez sur "Ajouter une catégorie"
3. Remplissez le formulaire
4. Cliquez sur "Enregistrer"

### 2. Ajouter un ebook

1. Accédez à http://localhost:8000/secureadmin/ebooks
2. Cliquez sur "Ajouter un ebook"
3. Remplissez le formulaire :
   - Titre, auteur, description
   - Prix (en FCFA)
   - Catégorie
   - Image de couverture (optionnel)
   - Fichier PDF (optionnel)
4. Cochez "Actif" pour le rendre visible
5. Cliquez sur "Enregistrer"

### 3. Tester un achat

1. Accédez au site public : http://localhost:8000
2. Cliquez sur un ebook
3. Cliquez sur "Ajouter au panier"
4. Accédez au panier
5. Cliquez sur "Procéder au paiement"
6. Remplissez le formulaire
7. Effectuez un paiement test avec Paydunya

---

## 🔒 Sécurité

### Protéger le dashboard admin

Le dashboard est actuellement accessible sans authentification. Pour le sécuriser :

#### Option 1 : Mot de passe simple

1. Ajoutez dans `.env` :
```env
ADMIN_PASSWORD=votre_mot_de_passe_securise
```

2. Créez le middleware (voir `ADMIN_DASHBOARD.md` pour le code complet)

3. Appliquez-le aux routes admin

#### Option 2 : Changer l'URL

Dans `routes/web.php`, changez :
```php
Route::prefix('secureadmin')  // Changez par quelque chose d'unique
```

#### Option 3 : Restriction par IP

Dans votre serveur web, limitez l'accès à `/secureadmin` à votre IP uniquement.

---

## 🛠️ Configuration avancée

### Configurer l'envoi d'emails

Dans `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Passer en production

1. **Mettre à jour `.env`** :
```env
APP_ENV=production
APP_DEBUG=false
PAYDUNYA_MODE=live
```

2. **Configurer les clés Paydunya de production**

3. **Optimiser l'application** :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Configurer HTTPS** sur votre serveur

---

## 📊 Structure du projet

```
ebook-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php       # Dashboard admin
│   │   ├── CheckoutController.php    # Paiement
│   │   ├── EbookController.php       # Catalogue
│   │   └── CartController.php        # Panier
│   ├── Models/
│   │   ├── Ebook.php
│   │   ├── Category.php
│   │   └── Order.php
│   └── Services/
│       └── PaydunyaService.php       # API Paydunya
├── resources/views/
│   ├── admin/                        # Vues admin
│   ├── checkout/                     # Vues paiement
│   ├── ebooks/                       # Vues catalogue
│   └── layouts/                      # Layouts
├── routes/
│   └── web.php                       # Routes
├── database/
│   ├── migrations/                   # Migrations
│   └── seeders/                      # Seeders
└── storage/
    └── app/public/                   # Fichiers uploadés
```

---

## 🐛 Dépannage

### Le site ne s'affiche pas

```bash
# Vérifier que le serveur est démarré
php artisan serve

# Vérifier les permissions
chmod -R 775 storage bootstrap/cache
```

### Erreur "Storage link not found"

```bash
php artisan storage:link
```

### Les images ne s'affichent pas

```bash
# Vérifier le lien symbolique
ls -la public/storage

# Si absent, le créer
php artisan storage:link
```

### Erreur de base de données

```bash
# Recréer la base de données
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate --seed
```

### Erreur Paydunya

1. Vérifiez vos clés dans `.env`
2. Vérifiez le mode (test/live)
3. Consultez les logs : `tail -f storage/logs/laravel.log`

---

## 📖 Documentation

### Guides disponibles

- `README.md` - Documentation générale
- `INTEGRATION_PAYDUNYA.md` - Documentation Paydunya
- `ADMIN_DASHBOARD.md` - Documentation du dashboard
- `TEST_PAYDUNYA.md` - Guide de test des paiements
- `QUICKSTART_PAYDUNYA.md` - Démarrage rapide Paydunya
- `RESUME_MODIFICATIONS.md` - Résumé des modifications

### Commandes utiles

```bash
# Lister les routes
php artisan route:list

# Nettoyer le cache
php artisan optimize:clear

# Voir les logs
tail -f storage/logs/laravel.log

# Accéder à Tinker (console Laravel)
php artisan tinker

# Créer un backup de la base de données
cp database/database.sqlite database/backup.sqlite
```

---

## 🎓 Tutoriels

### Ajouter un nouvel ebook

1. Préparez vos fichiers :
   - Image de couverture (JPG/PNG, max 2MB)
   - Fichier PDF (max 10MB)

2. Accédez au dashboard admin

3. Allez dans "Ebooks" → "Ajouter un ebook"

4. Remplissez tous les champs obligatoires

5. Uploadez l'image et le PDF

6. Cochez "Actif" pour le rendre visible

7. Cliquez sur "Enregistrer"

### Gérer une commande

1. Accédez à "Commandes" dans le dashboard

2. Cliquez sur une commande pour voir les détails

3. Vérifiez :
   - Informations client
   - Articles commandés
   - Statut de paiement

### Modifier un ebook

1. Allez dans "Ebooks"

2. Cliquez sur l'icône d'édition (crayon)

3. Modifiez les informations

4. Cliquez sur "Mettre à jour"

---

## 🚀 Prochaines étapes

### Immédiat
- [ ] Configurer les clés Paydunya
- [ ] Ajouter des catégories
- [ ] Ajouter des ebooks
- [ ] Tester un achat

### Court terme
- [ ] Sécuriser le dashboard admin
- [ ] Configurer l'envoi d'emails
- [ ] Personnaliser le design
- [ ] Ajouter votre logo

### Moyen terme
- [ ] Obtenir les clés Paydunya de production
- [ ] Configurer un nom de domaine
- [ ] Configurer HTTPS
- [ ] Passer en production

---

## 💡 Conseils

### Performance
- Activez le cache en production
- Optimisez les images avant upload
- Utilisez un CDN pour les fichiers statiques

### Sécurité
- Changez l'URL du dashboard admin
- Ajoutez une authentification
- Utilisez HTTPS en production
- Faites des backups réguliers

### Marketing
- Ajoutez des descriptions détaillées
- Utilisez des images de qualité
- Créez des promotions (prix_promo)
- Mettez en avant les bestsellers

---

## 📞 Support

### Ressources
- Documentation Laravel : https://laravel.com/docs
- Documentation Paydunya : https://paydunya.com/developers
- Tailwind CSS : https://tailwindcss.com/docs

### Logs
Consultez toujours les logs en cas de problème :
```bash
tail -f storage/logs/laravel.log
```

---

## ✅ Checklist de démarrage

- [ ] Dépendances installées (`composer install`)
- [ ] Fichier `.env` configuré
- [ ] Base de données créée et migrée
- [ ] Lien symbolique storage créé
- [ ] Clés Paydunya configurées
- [ ] Serveur démarré
- [ ] Dashboard admin accessible
- [ ] Première catégorie créée
- [ ] Premier ebook ajouté
- [ ] Test de paiement effectué

---

**Félicitations ! Votre plateforme est prête ! 🎉**

Pour toute question, consultez la documentation appropriée ou les logs de l'application.

**Bon développement ! 🚀**
