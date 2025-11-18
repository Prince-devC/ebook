@extends('layouts.app')

@section('title', 'Commander')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/css/intlTelInput.css">
@if($provider === 'kkiapay')
<script src="https://cdn.kkiapay.me/k.js"></script>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">💳 Finaliser ma commande</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Informations de facturation</h2>

                <form id="checkout-form">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="prenom" class="block text-gray-700 font-medium mb-2">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="nom" class="block text-gray-700 font-medium mb-2">Nom *</label>
                            <input type="text" id="nom" name="nom" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <p class="text-gray-500 text-sm mt-1">Vos ebooks seront envoyés à cette adresse</p>
                    </div>

                    <div class="mb-8">
                        <label for="telephone" class="block text-gray-700 font-medium mb-2">Numéro Mobile Money *</label>
                        <input type="tel" id="telephone" name="telephone" required placeholder="+229 XX XX XX XX"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <p class="text-gray-500 text-sm mt-1">Orange Money, MTN Money, Moov Money</p>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-600 mt-1 mr-3"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-semibold mb-1">Paiement 100% sécurisé</p>
                                <p>Vos informations sont cryptées et protégées.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="pay-button" class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-lock mr-2"></i>Payer {{ number_format($total, 0, ',', ' ') }} FCFA
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Récapitulatif</h2>
                
                <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                    @foreach($ebooks as $ebook)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded flex items-center justify-center flex-shrink-0">
                                @if($ebook->image)
                                    <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-full h-full object-cover rounded">
                                @else
                                    <span class="text-2xl">📖</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $ebook->titre }}</p>
                                <p class="text-xs text-gray-600">{{ $ebook->auteur }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800">{{ number_format($ebook->prix_final, 0, ',', ' ') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between text-gray-600">
                        <span>Sous-total ({{ count($ebooks) }} articles)</span>
                        <span class="font-semibold">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Frais de traitement</span>
                        <span class="font-semibold text-green-600">Gratuit</span>
                    </div>
                </div>

                <div class="flex justify-between text-2xl font-bold text-gray-800 mb-6">
                    <span>Total</span>
                    <span class="text-indigo-600">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Téléchargement immédiat</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Format PDF haute qualité</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Aucun frais supplémentaire</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/js/intlTelInput.min.js"></script>
<script>
const input = document.querySelector("#telephone");
const iti = window.intlTelInput(input, {
    initialCountry: "bj",
    preferredCountries: ["bj", "tg", "ci", "sn"],
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/js/utils.js"
});

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const prenom = document.getElementById('prenom').value;
    const nom = document.getElementById('nom').value;
    const email = document.getElementById('email').value;
    const telephone = iti.getNumber();
    
    if (!prenom || !nom || !email || !telephone) {
        alert('Veuillez remplir tous les champs');
        return;
    }

    const button = document.getElementById('pay-button');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';

    fetch('{{ route("checkout.initiate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            prenom: prenom,
            nom: nom,
            email: email,
            telephone: telephone
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            @if($provider === 'kkiapay')
            if (data.provider === 'kkiapay') {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-lock mr-2"></i>Payer {{ number_format($total, 0, ',', ' ') }} FCFA';
                
                openKkiapayWidget({
                    amount: data.data.amount,
                    position: "center",
                    callback: "",
                    data: "",
                    theme: "#0095ff",
                    sandbox: data.data.sandbox,
                    key: data.data.key,
                    phone: data.data.phone,
                    email: data.data.email,
                    name: data.data.name
                });
            }
            @else
            window.location.href = data.url;
            @endif
        } else {
            alert('Erreur: ' + data.message);
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-lock mr-2"></i>Payer {{ number_format($total, 0, ',', ' ') }} FCFA';
        }
    })
    .catch(error => {
        alert('Erreur: ' + error.message);
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-lock mr-2"></i>Payer {{ number_format($total, 0, ',', ' ') }} FCFA';
    });
});

@if($provider === 'kkiapay')
function addSuccessListener(callback) {
    window.addEventListener('message', function(event) {
        if (event.data && event.data.status === 'success') {
            callback(event.data);
        }
    });
}

addSuccessListener(function(response) {
    fetch('{{ route("checkout.kkiapay") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            transaction_id: response.transactionId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('Erreur: ' + data.message);
        }
    });
});
@endif
</script>
@endsection
