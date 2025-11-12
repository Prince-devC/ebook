# 🚀 Guide d'Installation sur LWS

## Configuration actuelle sur LWS

Vous avez déjà effectué :
- ✅ Clone du repository GitHub
- ✅ Installation de Composer
- ✅ Copie du fichier `.env.example` vers `.env`

## Étapes restantes

### 1. Générer la clé d'application

```bash
cd ~/htdocs/ebook
php artisan key:generate
```

### 2. Créer la base de données SQLite

```bash
touch database/database.sqlite
```

### 3. Configurer les permissions

```bash
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite
```

### 4. Modifier le fichier .env

Éditez le fichier `.env` :

```bash
nano .env
```

**Changements importants :**

```env
APP_NAME="Virtual World Digital"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.lws-hosting.com

DB_CONNECTION=sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp.lws.fr
MAIL_PORT=587
MAIL_USERNAME=votre-email@domaine.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votredomaine.com"
MAIL_FROM_NAME="Virtual World Digital"
```

Appuyez sur `Ctrl+O` pour sauvegarder, puis `Ctrl+X` pour quitter.

### 5. Exécuter les migrations

```bash
php artisan migrate:fresh --seed
```

Cette commande va créer :
- Les tables nécessaires
- 5 catégories d'ebooks
- 10 ebooks de démonstration
- 1 utilisateur admin

### 6. Créer le lien symbolique storage

```bash
php artisan storage:link
```

### 7. Optimiser l'application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Configuration de l'accès sans /public

### Option 1 : Utiliser le .htaccess (Déjà configuré)

Le fichier `.htaccess` à la racine du projet redirige automatiquement vers le dossier `public`.

**Accès :** `https://votredomaine.lws-hosting.com/ebook`

### Option 2 : Déplacer les fichiers (Recommandé pour production)

Si vous voulez que le site soit à la racine de votre domaine :

```bash
# 1. Déplacer le contenu de public vers htdocs
cd ~/htdocs
mv ebook/public/* .
mv ebook/public/.htaccess .

# 2. Modifier index.php
nano index.php
```

Dans `index.php`, changez ces lignes :

```php
// Ligne 17-18 environ
require __DIR__.'/ebook/vendor/autoload.php';
$app = require_once __DIR__.'/ebook/bootstrap/app.php';
```

**Accès :** `https://votredomaine.lws-hosting.com`

## Accès à l'application

### Site Public
- URL : `https://votredomaine.lws-hosting.com/ebook`
- Fonctionnalités : Catalogue, Panier, Commandes

### Panel Admin (Filament)
- URL : `https://votredomaine.lws-hosting.com/ebook/admin`
- Email : `admin@ebook.com`
- Mot de passe : `password`

**⚠️ IMPORTANT : Changez le mot de passe admin immédiatement !**

## Sécurité

### 1. Changer le mot de passe admin

Connectez-vous au panel admin puis :
```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@ebook.com')->first();
$user->password = bcrypt('VotreNouveauMotDePasse123!');
$user->save();
```

### 2. Protéger les fichiers sensibles

Le `.htaccess` bloque déjà l'accès aux fichiers sensibles (.env, composer.json, etc.)

### 3. Activer HTTPS

Sur LWS, activez le certificat SSL gratuit Let's Encrypt depuis votre panel.

## Vérifications

### Tester que tout fonctionne :

1. **Base de données :**
   ```bash
   php artisan migrate:status
   ```

2. **Vérifier les routes :**
   ```bash
   php artisan route:list
   ```

3. **Tester l'accès :**
   - Ouvrez `https://votredomaine.lws-hosting.com/ebook`
   - Vous devriez voir la page d'accueil

## Dépannage

### Erreur 500

```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Problème de permissions

```bash
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite
```

### Base de données non créée

```bash
rm database/database.sqlite
touch database/database.sqlite
chmod 664 database/database.sqlite
php artisan migrate:fresh --seed
```

## Performance

### Optimisation pour production

```bash
# Activer le mode production
php artisan optimize

# Mettre en cache les configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Mise à jour

Pour mettre à jour le code depuis GitHub :

```bash
cd ~/htdocs/ebook
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

## Support

Si vous rencontrez des problèmes :

1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez la console du navigateur (F12)
3. Contactez le support LWS si problème serveur

## 🎉 C'est terminé !

Votre marketplace d'ebooks **Virtual World Digital** est maintenant en ligne !

N'oubliez pas de :
- ✅ Changer le mot de passe admin
- ✅ Configurer les emails SMTP
- ✅ Activer le certificat SSL
- ✅ Ajouter vos propres ebooks depuis l'admin
