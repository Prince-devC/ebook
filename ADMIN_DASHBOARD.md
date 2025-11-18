# Dashboard Administration - Blade

## Vue d'ensemble

Dashboard d'administration simple et léger construit avec Blade (sans Filament) accessible sur `/secureadmin`.

## Accès

**URL :** `http://localhost:8000/secureadmin`

> Note : Actuellement sans authentification. Pour ajouter une protection, voir la section "Sécurité" ci-dessous.

## Fonctionnalités

### 📊 Dashboard
- Statistiques en temps réel
  - Total des ebooks
  - Total des catégories
  - Total des commandes
  - Revenu total
- Liste des 5 dernières commandes

### 📚 Gestion des Ebooks
- **Liste** : Affichage de tous les ebooks avec pagination
- **Créer** : Formulaire d'ajout d'ebook
  - Titre, auteur, description
  - Prix et prix promotionnel
  - Catégorie
  - Image de couverture (max 2MB)
  - Fichier PDF (max 10MB)
  - Options : Actif, Bestseller, Nouveau
- **Modifier** : Édition d'un ebook existant
- **Supprimer** : Suppression avec confirmation

### 📁 Gestion des Catégories
- **Liste** : Affichage de toutes les catégories
- **Créer** : Ajout d'une nouvelle catégorie
- **Modifier** : Édition d'une catégorie
- **Supprimer** : Suppression avec confirmation

### 🛒 Gestion des Commandes
- **Liste** : Affichage de toutes les commandes
- **Détail** : Vue détaillée d'une commande
  - Informations client
  - Articles commandés
  - Montant total
  - Statut de paiement

## Structure des fichiers

```
app/Http/Controllers/
└── AdminController.php          # Contrôleur principal

resources/views/admin/
├── layout.blade.php             # Layout principal
├── dashboard.blade.php          # Page d'accueil
├── ebooks/
│   ├── index.blade.php         # Liste des ebooks
│   ├── create.blade.php        # Formulaire création
│   └── edit.blade.php          # Formulaire édition
├── categories/
│   ├── index.blade.php         # Liste des catégories
│   ├── create.blade.php        # Formulaire création
│   └── edit.blade.php          # Formulaire édition
└── orders/
    ├── index.blade.php         # Liste des commandes
    └── show.blade.php          # Détail commande
```

## Routes

### Dashboard
```
GET  /secureadmin                    # Dashboard principal
```

### Ebooks
```
GET    /secureadmin/ebooks           # Liste
GET    /secureadmin/ebooks/create    # Formulaire création
POST   /secureadmin/ebooks           # Enregistrer
GET    /secureadmin/ebooks/{id}/edit # Formulaire édition
PUT    /secureadmin/ebooks/{id}      # Mettre à jour
DELETE /secureadmin/ebooks/{id}      # Supprimer
```

### Catégories
```
GET    /secureadmin/categories           # Liste
GET    /secureadmin/categories/create    # Formulaire création
POST   /secureadmin/categories           # Enregistrer
GET    /secureadmin/categories/{id}/edit # Formulaire édition
PUT    /secureadmin/categories/{id}      # Mettre à jour
DELETE /secureadmin/categories/{id}      # Supprimer
```

### Commandes
```
GET /secureadmin/orders        # Liste
GET /secureadmin/orders/{id}   # Détail
```

## Technologies utilisées

- **Backend** : Laravel 12
- **Frontend** : Blade Templates
- **CSS** : Tailwind CSS (via CDN)
- **Icons** : Font Awesome 6

## Sécurité

### Ajouter une authentification

Pour protéger l'accès au dashboard, ajoutez un middleware :

1. **Créer un middleware simple** :

```php
// app/Http/Middleware/AdminAuth.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $password = $request->session()->get('admin_authenticated');
        
        if ($password !== true) {
            return redirect()->route('admin.login');
        }
        
        return $next($request);
    }
}
```

2. **Enregistrer le middleware** dans `bootstrap/app.php` :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin.auth' => \App\Http\Middleware\AdminAuth::class,
    ]);
})
```

3. **Appliquer aux routes** dans `routes/web.php` :

```php
Route::prefix('secureadmin')->name('admin.')->middleware('admin.auth')->group(function () {
    // ... routes admin
});
```

4. **Créer une page de login** :

```php
// routes/web.php
Route::get('/secureadmin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/secureadmin/login', function (Request $request) {
    if ($request->password === env('ADMIN_PASSWORD', 'admin123')) {
        $request->session()->put('admin_authenticated', true);
        return redirect()->route('admin.dashboard');
    }
    return back()->with('error', 'Mot de passe incorrect');
})->name('admin.login.post');
```

5. **Ajouter dans `.env`** :

```env
ADMIN_PASSWORD=votre_mot_de_passe_securise
```

## Personnalisation

### Changer les couleurs

Modifiez les classes Tailwind dans `resources/views/admin/layout.blade.php` :

```html
<!-- Sidebar -->
<aside class="w-64 bg-indigo-800 text-white">  <!-- Changer bg-gray-800 -->

<!-- Boutons -->
<button class="bg-indigo-600 hover:bg-indigo-700">  <!-- Changer bg-blue-600 -->
```

### Ajouter un logo

Dans `resources/views/admin/layout.blade.php` :

```html
<div class="p-4 flex items-center gap-3">
    <img src="/logo.png" alt="Logo" class="w-10 h-10">
    <h1 class="text-2xl font-bold">Admin</h1>
</div>
```

### Modifier la pagination

Laravel utilise Tailwind par défaut. Pour personnaliser :

```bash
php artisan vendor:publish --tag=laravel-pagination
```

## Avantages vs Filament

### ✅ Avantages
- **Léger** : Pas de dépendances lourdes
- **Simple** : Code facile à comprendre et modifier
- **Rapide** : Chargement instantané
- **Personnalisable** : Contrôle total sur le design
- **Pas de courbe d'apprentissage** : Blade standard

### ⚠️ Inconvénients
- Pas de fonctionnalités avancées (filtres, exports, etc.)
- Pas de gestion des permissions intégrée
- Pas de widgets complexes

## Migration depuis Filament

Les fichiers Filament sont toujours présents dans `app/Filament/` mais ne sont plus utilisés. Vous pouvez les supprimer :

```bash
rm -rf app/Filament
rm -rf app/Providers/Filament
```

Pour désinstaller complètement Filament :

```bash
composer remove filament/filament
```

## Développement futur

### Fonctionnalités à ajouter

1. **Authentification complète**
   - Login/Logout
   - Gestion des utilisateurs admin
   - Rôles et permissions

2. **Statistiques avancées**
   - Graphiques de ventes
   - Ebooks les plus vendus
   - Revenus par période

3. **Filtres et recherche**
   - Recherche d'ebooks
   - Filtres par catégorie, statut
   - Tri personnalisé

4. **Export de données**
   - Export CSV des commandes
   - Rapports PDF

5. **Gestion des fichiers**
   - Gestionnaire de médias
   - Optimisation d'images

## Support

Pour toute question ou problème :
1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez les routes : `php artisan route:list`
3. Testez les permissions : `storage/` et `public/storage` doivent être accessibles en écriture

## Commandes utiles

```bash
# Vider le cache
php artisan optimize:clear

# Créer le lien symbolique pour le storage
php artisan storage:link

# Lister les routes
php artisan route:list --path=secureadmin

# Voir les logs en temps réel
tail -f storage/logs/laravel.log
```
