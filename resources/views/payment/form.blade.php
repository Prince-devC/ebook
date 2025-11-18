<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement FedaPay</title>
</head>
<body>
    <h1>Paiement</h1>
    
    <form id="payment-form">
        @csrf
        <input type="number" name="amount" placeholder="Montant" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="text" name="firstname" placeholder="Prénom" required>
        <input type="text" name="lastname" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone_number" placeholder="Téléphone">
        <button type="submit">Payer</button>
    </form>

    <script>
        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('{{ route("payment.initiate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.url;
                } else {
                    alert('Erreur: ' + result.message);
                }
            } catch (error) {
                alert('Erreur de connexion');
            }
        });
    </script>
</body>
</html>
