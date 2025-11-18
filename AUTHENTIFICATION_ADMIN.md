# Authentification Admin

## Vue d'ensemble

Le dashboard admin est maintenant protégé par un système d'authentification avec :
- ✅ Connexion par mot de passe
- ✅ Déconnexion manuelle
- ✅ Déconnexion automatique après 30 minutes d'inactivité

## Configuration

### Mot de passe admin

Le mot de passe est défini dans le fichier `.env` :

```env
ADMIN_PASSWORD=admin123
```

⚠️ **Important** : Changez ce mot de passe par défaut !

### Recommandations de sécurité

```env
# Utilisez un mot de passe fort
ADMIN_PASSWORD=VotreMotDePasseSecurise2024!
```

## Utilisation

### 1. Connexion

**URL** : http://localhost:8000/secureadmin/login

1. Accédez à l'URL de connexion
2. Entrez le mot de passe configuré dans `.env`
3. Cliquez sur "Se connecter"
4. Vous êtes redirigé vers le dashboard

### 2. Déconnexion manuelle

Dans le dashboard admin :
1. Cliquez sur le bouton "Déconnexion" dans la sidebar (en rouge)
2. Vous êtes redirigé vers la page de connexion

### 3. Déconnexion automatique

Le système vous déconnecte automatiquement après **30 minutes d'inactivité**.

**Inactivité** = Aucune action effectuée dans le dashboard pendant 30 minutes.

Lorsque vous êtes déconnecté automatiquement :
- Un message s'affiche : "Session expirée après 30 minutes d'inactivité"
- Vous devez vous reconnecter

## Fonctionnement technique

### Middleware AdminAuth

Le middleware `app/Http/Middleware/AdminAuth.php` :
1. Vérifie si l'utilisateur est authentifié
2. Vérifie le temps d'inactivité (30 minutes = 1800 secondes)
3. Met à jour le timestamp d'activité à chaque requête
4. Redirige vers la page de login si non authentifié ou inactif

### Session

Les données stockées en session :
- `admin_authenticated` : État de connexion (true/false)
- `admin_last_activity` : Timestamp de la dernière activité

### Routes protégées

Toutes les routes sous `/secureadmin` (sauf login/logout) sont protégées par le middleware `admin.auth`.

## Personnalisation

### Changer le délai d'inactivité

Dans `app/Http/Middleware/AdminAuth.php`, ligne 16 :

```php
// 30 minutes = 1800 secondes
if ($lastActivity && (time() - $lastActivity) > 1800) {
    // Déconnexion
}
```

Exemples :
- 15 minutes : `900`
- 1 heure : `3600`
- 2 heures : `7200`

### Changer le message d'expiration

Dans `app/Http/Middleware/AdminAuth.php`, ligne 18 :

```php
return redirect()->route('admin.login')
    ->with('error', 'Votre message personnalisé');
```

## Sécurité avancée

### 1. Utiliser un hash pour le mot de passe

Modifiez `routes/web.php` :

```php
use Illuminate\Support\Facades\Hash;

Route::post('/secureadmin/login', function (Request $request) {
    // Créer le hash : Hash::make('votre_mot_de_passe')
    if (Hash::check($request->password, env('ADMIN_PASSWORD_HASH'))) {
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_last_activity', time());
        return redirect()->route('admin.dashboard');
    }
    return back()->with('error', 'Mot de passe incorrect');
})->name('admin.login.post');
```

Dans `.env` :
```env
ADMIN_PASSWORD_HASH=$2y$12$...votre_hash...
```

### 2. Limiter les tentatives de connexion

Ajoutez un compteur de tentatives :

```php
Route::post('/secureadmin/login', function (Request $request) {
    $attempts = $request->session()->get('login_attempts', 0);
    
    if ($attempts >= 5) {
        return back()->with('error', 'Trop de tentatives. Réessayez dans 15 minutes.');
    }
    
    if ($request->password === env('ADMIN_PASSWORD')) {
        $request->session()->forget('login_attempts');
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_last_activity', time());
        return redirect()->route('admin.dashboard');
    }
    
    $request->session()->put('login_attempts', $attempts + 1);
    return back()->with('error', 'Mot de passe incorrect');
})->name('admin.login.post');
```

### 3. Restriction par IP

Dans `app/Http/Middleware/AdminAuth.php` :

```php
public function handle(Request $request, Closure $next)
{
    $allowedIps = ['127.0.0.1', 'votre.ip.publique'];
    
    if (!in_array($request->ip(), $allowedIps)) {
        abort(403, 'Accès refusé');
    }
    
    // ... reste du code
}
```

## Tests

### Test de connexion

1. Accédez à http://localhost:8000/secureadmin
2. Vous devriez être redirigé vers `/secureadmin/login`
3. Entrez le mot de passe : `admin123`
4. Vous devriez accéder au dashboard

### Test de déconnexion manuelle

1. Connectez-vous au dashboard
2. Cliquez sur "Déconnexion"
3. Vous devriez être redirigé vers la page de login

### Test de déconnexion automatique

1. Connectez-vous au dashboard
2. Attendez 30 minutes sans rien faire
3. Essayez d'accéder à une page du dashboard
4. Vous devriez être déconnecté avec le message d'expiration

### Test rapide (pour développement)

Modifiez temporairement le délai à 60 secondes :

```php
// Dans AdminAuth.php
if ($lastActivity && (time() - $lastActivity) > 60) { // 60 secondes
```

## Dépannage

### Problème : "Session expirée" immédiatement

**Cause** : Le driver de session n'est pas configuré correctement.

**Solution** :
```bash
php artisan session:table
php artisan migrate
```

Vérifiez dans `.env` :
```env
SESSION_DRIVER=database
```

### Problème : Impossible de se connecter

**Cause** : Mot de passe incorrect ou non défini.

**Solution** :
1. Vérifiez `.env` : `ADMIN_PASSWORD=admin123`
2. Nettoyez le cache : `php artisan config:clear`

### Problème : Déconnexion trop rapide

**Cause** : Le délai d'inactivité est trop court.

**Solution** : Augmentez le délai dans `AdminAuth.php`

## Fichiers modifiés/créés

```
✅ app/Http/Middleware/AdminAuth.php          (créé)
✅ resources/views/admin/login.blade.php      (créé)
✅ resources/views/admin/layout.blade.php     (modifié)
✅ routes/web.php                             (modifié)
✅ bootstrap/app.php                          (modifié)
✅ .env                                       (modifié)
✅ AUTHENTIFICATION_ADMIN.md                  (créé)
```

## Résumé

- 🔐 **Connexion** : http://localhost:8000/secureadmin/login
- 🔑 **Mot de passe par défaut** : `admin123` (à changer !)
- ⏱️ **Déconnexion auto** : 30 minutes d'inactivité
- 🚪 **Déconnexion manuelle** : Bouton dans la sidebar

**⚠️ N'oubliez pas de changer le mot de passe par défaut !**
