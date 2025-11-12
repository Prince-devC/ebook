# 📚 Virtual World Digital - Marketplace d'Ebooks Laravel + Filament

Marketplace d'ebooks moderne développée avec **Laravel 12** et **Filament PHP 3**. Une application complète avec interface publique et panneau d'administration.

## 🚀 Fonctionnalités

### Frontend (Public)
- ✅ Page d'accueil attractive avec bestsellers et nouveautés
- ✅ Catalogue complet avec filtres et recherche
- ✅ Pages détails des ebooks
- ✅ Système de panier (session)
- ✅ Processus de commande complet
- ✅ Page de confirmation
- ✅ Design responsive (Tailwind CSS)
- ✅ Interface moderne avec Alpine.js

### Backend (Administration Filament)
- ✅ Gestion complète des ebooks (CRUD)
- ✅ Gestion des catégories
- ✅ Suivi des commandes
- ✅ Upload d'images et fichiers PDF
- ✅ Statistiques et filtres avancés
- ✅ Interface intuitive et moderne

## 💻 Technologies

- **Laravel 12** - Framework PHP
- **Filament PHP 3** - Panneau d'administration
- **Tailwind CSS** - Styling
- **Alpine.js** - Interactions JavaScript
- **MySQL/SQLite** - Base de données
- **Blade** - Template engine

## 📋 Prérequis

- PHP 8.2+
- Composer
- MySQL 5.7+ ou SQLite
- MAMP/WAMP/XAMPP ou serveur local

## 🛠️ Installation

### 1. Installer les dépendances

Les dépendances sont déjà installées. Si nécessaire :

```bash
composer install
```

### 2. Configuration de l'environnement

Le fichier `.env` est déjà configuré pour SQLite. Pour utiliser MySQL :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebook_empire
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. Exécuter les migrations et seeders

Les migrations sont déjà effectuées. Pour recréer la base :

```bash
php artisan migrate:fresh --seed
```

Cela créera :
- 5 catégories
- 10 ebooks (domaine public et Creative Commons)
- 1 utilisateur admin : **admin@ebook.com** / **password**

### 4. Lancer le serveur

```bash
php artisan serve
```

L'application sera accessible sur : **http://localhost:8000**

## 🎯 Utilisation

### Accéder au site public

```
http://localhost:8000
```

Vous pouvez :
- Parcourir le catalogue
- Ajouter des ebooks au panier
- Passer une commande (simulation de paiement)

### Accéder à l'administration Filament

```
http://localhost:8000/admin
```

**Identifiants** :
- Email : `admin@ebook.com`
- Mot de passe : `password`

Depuis l'admin, vous pouvez :
- **Ebooks** : Ajouter, modifier, supprimer des ebooks avec upload d'images et PDF
- **Catégories** : Gérer les catégories d'ebooks
- **Commandes** : Voir toutes les commandes passées

## 📁 Structure du Projet

```
ebook-laravel/
├── app/
│   ├── Filament/Resources/    # Ressources admin Filament
│   ├── Http/Controllers/      # Contrôleurs frontend
│   └── Models/                # Modèles Eloquent
├── database/
│   ├── migrations/            # Migrations
│   └── seeders/               # Seeders (données initiales)
├── resources/views/
│   ├── layouts/              # Layout principal
│   ├── home.blade.php       # Page d'accueil
│   ├── ebooks/              # Vues catalogue et détails
│   ├── cart/                # Vue panier
│   └── checkout/            # Vues commande
├── routes/
│   └── web.php              # Routes de l'application
└── public/storage/          # Stockage public (images, PDFs)
```

## 💰 Système de Prix

- **Devise** : FCFA (Franc CFA)
- **Prix minimum** : 2500 FCFA
- Les ebooks peuvent avoir un prix promotionnel
- Aucun frais supplémentaire

## 🎨 Personnalisation

### Changer les couleurs

Les couleurs sont gérées par Tailwind CSS. Les couleurs principales utilisent :
- `indigo-600` : Couleur principale
- `purple-600` : Couleur secondaire
- `green-600` : Prix promotionnels

### Ajouter des ebooks

Depuis l'admin Filament :
1. Aller dans **Ebooks** > **Créer**
2. Remplir les informations
3. Uploader une image (optionnel)
4. Uploader le PDF (optionnel)
5. Sauvegarder

### Catégories personnalisées

Depuis l'admin Filament :
1. Aller dans **Catégories** > **Créer**
2. Entrer le nom (le slug sera généré automatiquement)
3. Ajouter une description
4. Sauvegarder

## 🔒 Sécurité

- ✅ Protection CSRF sur tous les formulaires
- ✅ Validation des données
- ✅ Requêtes préparées PDO
- ✅ Échappement HTML automatique
- ✅ Upload de fichiers sécurisé

## 📊 Base de Données

### Tables principales

- **categories** : Catégories d'ebooks
- **ebooks** : Catalogue des ebooks
- **orders** : Commandes clients
- **order_items** : Détails des commandes
- **users** : Utilisateurs admin

## 🚧 Évolutions Possibles

- [ ] Intégration de vrais moyens de paiement (Stripe, PayPal, etc.)
- [ ] Envoi d'emails de confirmation
- [ ] Système de téléchargement sécurisé avec liens temporaires
- [ ] Tableau de bord avec statistiques avancées
- [ ] Système de notation et avis clients
- [ ] Espace client avec historique des achats
- [ ] Multi-devise
- [ ] Newsletter

## 🐛 Dépannage

### Erreur 500

Vérifier les logs :
```bash
tail -f storage/logs/laravel.log
```

### Problème de permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### Problème de storage

```bash
php artisan storage:link
```

## 📝 Notes Importantes

- Le système de paiement est actuellement simulé (toutes les commandes sont marquées comme "payées")
- Les emails ne sont pas envoyés (configurés en mode `log`)
- Les fichiers PDF et images doivent être uploadés via l'administration Filament

## 🤝 Support

Pour toute question ou problème :
- Vérifier les logs Laravel : `storage/logs/laravel.log`
- Vérifier la console du navigateur pour les erreurs JavaScript
- S'assurer que MAMP est bien démarré et que la base de données est accessible

## 📜 Licence

Projet éducatif - Libre d'utilisation

## 👨‍💻 Développement

Développé avec ❤️ en utilisant :
- Laravel 12
- Filament PHP 3
- Tailwind CSS
- Alpine.js
