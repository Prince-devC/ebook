# Intégration Paydunya Softpay API

## Vue d'ensemble

Ce projet utilise l'API Softpay de Paydunya pour gérer les paiements Mobile Money (Orange Money, MTN Money, Moov Money).

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables dans votre fichier `.env` :

```env
PAYDUNYA_MASTER_KEY=your_master_key_here
PAYDUNYA_PRIVATE_KEY=your_private_key_here
PAYDUNYA_TOKEN=your_token_here
PAYDUNYA_MODE=test
```

**Mode de paiement :**
- `test` : Mode sandbox pour les tests
- `live` : Mode production pour les paiements réels

### 2. Obtenir vos clés API

1. Créez un compte sur [Paydunya](https://paydunya.com)
2. Accédez à votre tableau de bord
3. Récupérez vos clés API :
   - Master Key
   - Private Key
   - Token

## Architecture

### Fichiers créés/modifiés

1. **Configuration**
   - `config/paydunya.php` : Configuration Paydunya

2. **Service**
   - `app/Services/PaydunyaService.php` : Service d'intégration API

3. **Contrôleur**
   - `app/Http/Controllers/CheckoutController.php` : Gestion du processus de paiement

4. **Routes**
   - `routes/web.php` : Routes de paiement

5. **Vue**
   - `resources/views/checkout/index.blade.php` : Formulaire de paiement

## Flux de paiement

1. **Initiation** : L'utilisateur remplit le formulaire de checkout
2. **Création de facture** : Une facture est créée via l'API Paydunya
3. **Redirection** : L'utilisateur est redirigé vers la page de paiement Paydunya
4. **Paiement** : L'utilisateur effectue le paiement via Mobile Money
5. **Callback** : Paydunya redirige vers notre callback avec le token
6. **Confirmation** : Nous vérifions le statut du paiement
7. **Finalisation** : La commande est créée et l'utilisateur reçoit ses ebooks

## Endpoints API

### Créer une facture
```
POST https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create
```

### Confirmer une facture
```
GET https://app.paydunya.com/sandbox-api/v1/checkout-invoice/confirm/{token}
```

## Méthodes du service

### `createInvoice($data)`

Crée une nouvelle facture de paiement.

**Paramètres :**
```php
[
    'amount' => 5000,
    'description' => 'Achat de 2 ebook(s)',
    'cancel_url' => 'https://example.com/checkout',
    'return_url' => 'https://example.com/checkout/callback',
    'custom_data' => [
        'email' => 'client@example.com',
        'nom' => 'Doe',
        'prenom' => 'John',
    ]
]
```

**Retour :**
```php
[
    'response_code' => '00',
    'response_text' => 'https://app.paydunya.com/checkout/...',
    'token' => 'abc123...'
]
```

### `confirmInvoice($token)`

Vérifie le statut d'une facture.

**Retour :**
```php
[
    'status' => 'completed',
    'invoice' => [...],
    'custom_data' => [...]
]
```

## Sécurité

- Les clés API ne doivent jamais être exposées côté client
- Toutes les requêtes API sont effectuées côté serveur
- Les données sensibles sont stockées en session
- La vérification du paiement est obligatoire avant la création de commande

## Tests

### Mode Test
En mode test, utilisez les numéros de test fournis par Paydunya :
- Orange Money : +229 XX XX XX XX
- MTN Money : +229 XX XX XX XX
- Moov Money : +229 XX XX XX XX

### Passage en production

1. Changez `PAYDUNYA_MODE=live` dans `.env`
2. Remplacez les clés de test par les clés de production
3. Testez avec de petits montants avant le lancement

## Support

- Documentation Paydunya : https://paydunya.com/developers
- Support : support@paydunya.com

## Migration depuis Kkiapay

Les fichiers suivants ont été modifiés lors de la migration :
- ✅ `CheckoutController.php` : Remplacé Kkiapay par Paydunya
- ✅ `checkout/index.blade.php` : Supprimé le widget Kkiapay
- ✅ `routes/web.php` : Mis à jour les routes
- ✅ `.env` : Remplacé les clés Kkiapay par Paydunya
- ✅ `config/kkiapay.php` : Désactivé (conservé pour référence)
