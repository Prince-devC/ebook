# Guide de test - Intégration Paydunya

## Prérequis

1. Assurez-vous que vos clés API Paydunya sont configurées dans `.env`
2. Le serveur Laravel doit être démarré : `php artisan serve`
3. Mode test activé : `PAYDUNYA_MODE=test`

## Étapes de test

### 1. Ajouter des ebooks au panier

1. Accédez à la page d'accueil : `http://localhost:8000`
2. Parcourez les ebooks disponibles
3. Cliquez sur "Ajouter au panier" pour plusieurs ebooks
4. Vérifiez que le compteur du panier s'incrémente

### 2. Accéder au panier

1. Cliquez sur l'icône du panier
2. Vérifiez que tous les ebooks ajoutés sont présents
3. Vérifiez que le total est correct
4. Cliquez sur "Procéder au paiement"

### 3. Remplir le formulaire de checkout

1. Remplissez le formulaire :
   - Prénom : John
   - Nom : Doe
   - Email : test@example.com
   - Téléphone : +229 XX XX XX XX (numéro de test Paydunya)

2. Vérifiez le récapitulatif de commande à droite
3. Cliquez sur "Payer X FCFA"

### 4. Redirection vers Paydunya

1. Vous devriez être redirigé vers la page de paiement Paydunya
2. La page affiche :
   - Le montant à payer
   - La description de la commande
   - Les options de paiement Mobile Money

### 5. Effectuer le paiement (Mode Test)

1. Sélectionnez votre opérateur Mobile Money
2. Entrez le numéro de test fourni par Paydunya
3. Confirmez le paiement
4. En mode test, le paiement est automatiquement approuvé

### 6. Callback et confirmation

1. Après le paiement, vous êtes redirigé vers `/commander/callback`
2. Le système vérifie le statut du paiement
3. Si le paiement est confirmé :
   - Une commande est créée dans la base de données
   - Le panier est vidé
   - Vous êtes redirigé vers la page de succès

### 7. Page de succès

1. Vérifiez que la page affiche :
   - Le numéro de commande
   - La liste des ebooks achetés
   - Les liens de téléchargement
   - L'email de confirmation

## Vérifications dans la base de données

```bash
php artisan tinker
```

```php
// Vérifier les commandes
App\Models\Order::latest()->first();

// Vérifier les items de commande
App\Models\OrderItem::latest()->get();
```

## Tests d'erreur

### Test 1 : Panier vide
1. Videz le panier
2. Essayez d'accéder à `/commander`
3. Vous devriez être redirigé vers le panier avec un message d'erreur

### Test 2 : Formulaire incomplet
1. Laissez un champ vide
2. Essayez de soumettre
3. Le navigateur devrait afficher une erreur de validation

### Test 3 : Annulation du paiement
1. Sur la page Paydunya, cliquez sur "Annuler"
2. Vous devriez être redirigé vers `/commander`
3. Le panier devrait toujours contenir vos articles

## Logs à vérifier

```bash
tail -f storage/logs/laravel.log
```

Recherchez :
- Les requêtes API vers Paydunya
- Les erreurs éventuelles
- Les confirmations de paiement

## Checklist de validation

- [ ] Ajout au panier fonctionne
- [ ] Affichage du panier correct
- [ ] Formulaire de checkout valide les données
- [ ] Redirection vers Paydunya réussie
- [ ] Paiement test accepté
- [ ] Callback traité correctement
- [ ] Commande créée en base de données
- [ ] Panier vidé après paiement
- [ ] Page de succès affichée
- [ ] Email de confirmation envoyé (si configuré)

## Problèmes courants

### Erreur : "Configuration cache cleared successfully"
**Solution :** Exécutez `php artisan config:clear`

### Erreur : "Erreur lors de la création de la facture"
**Solution :** Vérifiez vos clés API dans `.env`

### Erreur : "Token invalide"
**Solution :** Le callback n'a pas reçu le token. Vérifiez l'URL de callback dans Paydunya

### Paiement non confirmé
**Solution :** Vérifiez que le mode est bien `test` et utilisez les numéros de test

## Passage en production

Avant de passer en production :

1. [ ] Testez tous les scénarios en mode test
2. [ ] Configurez les clés de production dans `.env`
3. [ ] Changez `PAYDUNYA_MODE=live`
4. [ ] Testez avec un petit montant réel
5. [ ] Configurez l'envoi d'emails
6. [ ] Activez les logs de production
7. [ ] Configurez les webhooks Paydunya (optionnel)

## Support

En cas de problème :
1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez la documentation : `INTEGRATION_PAYDUNYA.md`
3. Contactez le support Paydunya : support@paydunya.com
