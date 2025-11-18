# 🚀 Démarrage rapide - Paydunya Softpay

## Configuration en 5 minutes

### 1. Obtenir vos clés API Paydunya

1. Créez un compte sur [https://paydunya.com](https://paydunya.com)
2. Connectez-vous à votre tableau de bord
3. Allez dans **Paramètres** → **API Keys**
4. Copiez vos clés :
   - Master Key
   - Private Key
   - Token

### 2. Configurer votre projet

Ouvrez le fichier `.env` et ajoutez vos clés :

```env
PAYDUNYA_MASTER_KEY=votre_master_key
PAYDUNYA_PRIVATE_KEY=votre_private_key
PAYDUNYA_TOKEN=votre_token
PAYDUNYA_MODE=test
```

### 3. Nettoyer le cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Démarrer le serveur

```bash
php artisan serve
```

### 5. Tester le paiement

1. Accédez à `http://localhost:8000`
2. Ajoutez des ebooks au panier
3. Cliquez sur "Procéder au paiement"
4. Remplissez le formulaire
5. Effectuez un paiement test

## Numéros de test (Mode Sandbox)

Utilisez ces numéros pour tester les paiements :

- **Orange Money** : +229 XX XX XX XX
- **MTN Money** : +229 XX XX XX XX
- **Moov Money** : +229 XX XX XX XX

> Consultez la documentation Paydunya pour les numéros de test exacts

## Vérification rapide

### Vérifier les routes
```bash
php artisan route:list --path=command
```

Vous devriez voir :
- `GET /commander` - Page de checkout
- `POST /commander/initier` - Initiation du paiement
- `GET /commander/callback` - Retour de paiement
- `GET /commande/confirmation/{numero}` - Page de succès

### Vérifier la configuration
```bash
php artisan tinker
```

```php
config('paydunya.master_key')
config('paydunya.mode')
```

## Structure des fichiers

```
app/
├── Http/Controllers/
│   └── CheckoutController.php    # Gestion des paiements
└── Services/
    └── PaydunyaService.php        # Service API Paydunya

config/
└── paydunya.php                   # Configuration

resources/views/checkout/
├── index.blade.php                # Formulaire de paiement
└── success.blade.php              # Page de succès

routes/
└── web.php                        # Routes de paiement
```

## Flux de paiement simplifié

```
┌─────────────┐
│   Panier    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Formulaire │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Paydunya   │ ← Redirection
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Paiement   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Callback   │ ← Vérification
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Succès    │
└─────────────┘
```

## Commandes utiles

### Voir les logs en temps réel
```bash
tail -f storage/logs/laravel.log
```

### Vider le cache
```bash
php artisan optimize:clear
```

### Vérifier les commandes
```bash
php artisan tinker
```
```php
App\Models\Order::latest()->first()
```

## Problèmes courants

### ❌ Erreur : "Erreur lors de la création de la facture"
**Solution :** Vérifiez vos clés API dans `.env`

### ❌ Erreur : "Token invalide"
**Solution :** Vérifiez que l'URL de callback est correcte

### ❌ Erreur : "Panier vide"
**Solution :** Ajoutez des ebooks au panier avant de commander

## Passage en production

Quand vous êtes prêt pour la production :

1. Obtenez vos clés de production sur Paydunya
2. Mettez à jour `.env` :
   ```env
   PAYDUNYA_MODE=live
   PAYDUNYA_MASTER_KEY=votre_master_key_prod
   PAYDUNYA_PRIVATE_KEY=votre_private_key_prod
   PAYDUNYA_TOKEN=votre_token_prod
   ```
3. Testez avec un petit montant réel
4. Activez les logs de production

## Support

- 📖 Documentation complète : `INTEGRATION_PAYDUNYA.md`
- 🧪 Guide de test : `TEST_PAYDUNYA.md`
- 📝 Changelog : `CHANGELOG_PAYDUNYA.md`
- 🌐 Documentation Paydunya : https://paydunya.com/developers
- 📧 Support Paydunya : support@paydunya.com

## Checklist de démarrage

- [ ] Compte Paydunya créé
- [ ] Clés API obtenues
- [ ] Fichier `.env` configuré
- [ ] Cache nettoyé
- [ ] Serveur démarré
- [ ] Test de paiement effectué
- [ ] Commande créée en base de données
- [ ] Page de succès affichée

## Prêt à démarrer ? 🎉

Vous avez tout configuré ! Lancez votre premier test de paiement et consultez la documentation complète pour plus de détails.

**Bon développement ! 🚀**
