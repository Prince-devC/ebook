@extends('layouts.app')

@section('title', 'Commande confirmée')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Message de succès -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
            <i class="fas fa-check-circle text-6xl text-green-600"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-4">✨ Commande Confirmée !</h1>
        <p class="text-xl text-gray-600">Merci pour votre achat</p>
    </div>

    <!-- Détails de la commande -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="border-b border-gray-200 pb-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-600 mb-1">Numéro de commande</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $order->numero_commande }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 mb-1">Date</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-1">Client</p>
                    <p class="font-semibold text-gray-800">{{ $order->prenom }} {{ $order->nom }}</p>
                    <p class="text-gray-600">{{ $order->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Méthode de paiement</p>
                    <p class="font-semibold text-gray-800">
                        @if($order->methode_paiement == 'carte')
                            <i class="fas fa-credit-card mr-1"></i>Carte bancaire
                        @elseif($order->methode_paiement == 'paypal')
                            <i class="fab fa-paypal mr-1"></i>PayPal
                        @else
                            <i class="fas fa-mobile-alt mr-1"></i>Mobile Money
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Liste des ebooks -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Vos Ebooks</h2>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-16 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded flex items-center justify-center flex-shrink-0">
                            @if($item->ebook && $item->ebook->image)
                                <img src="/storage/{{ $item->ebook->image }}" alt="{{ $item->titre_ebook }}" class="w-full h-full object-cover rounded">
                            @else
                                <span class="text-3xl">📖</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800">{{ $item->titre_ebook }}</h3>
                            @if($item->ebook)
                                <p class="text-sm text-gray-600">{{ $item->ebook->auteur }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-file-pdf mr-1"></i>{{ $item->ebook->pages }} pages • {{ $item->ebook->langue }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-800">{{ number_format($item->prix, 0, ',', ' ') }} FCFA</p>
                            @if($item->ebook && $item->ebook->fichier_pdf)
                                <a href="#" class="inline-block mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    <i class="fas fa-download mr-1"></i>Télécharger
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Total -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-gray-800">Total payé</span>
                <span class="text-3xl font-bold text-indigo-600">{{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-3">📧 Prochaines étapes</h3>
        <ul class="space-y-2 text-gray-700">
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <span>Un email de confirmation a été envoyé à <strong>{{ $order->email }}</strong></span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <span>Vous pouvez télécharger vos ebooks immédiatement en cliquant sur les liens ci-dessus</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <span>Les liens de téléchargement sont également disponibles dans votre email</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <span>Conservez votre numéro de commande pour toute référence future</span>
            </li>
        </ul>
    </div>

    <!-- Avantages -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">🎁 Vos avantages</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-start">
                <i class="fas fa-download text-indigo-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold text-gray-800">Téléchargements illimités</p>
                    <p class="text-sm text-gray-600">Accédez à vos ebooks à tout moment</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-mobile-alt text-indigo-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold text-gray-800">Compatible tous appareils</p>
                    <p class="text-sm text-gray-600">Lisez sur mobile, tablette ou PC</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-star text-indigo-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold text-gray-800">Qualité garantie</p>
                    <p class="text-sm text-gray-600">Format PDF haute résolution</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="text-center space-x-4">
        <a href="{{ route('ebooks.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
            <i class="fas fa-book mr-2"></i>Découvrir plus d'ebooks
        </a>
        <a href="{{ route('home') }}" class="inline-block bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-home mr-2"></i>Retour à l'accueil
        </a>
    </div>

    <!-- Support -->
    <div class="text-center mt-8 text-gray-600">
        <p class="mb-2">Besoin d'aide ? Notre équipe est là pour vous !</p>
        <p>
            <i class="fas fa-envelope mr-2"></i>
            <a href="mailto:contact@virtualworlddigital.com" class="text-indigo-600 hover:text-indigo-800">contact@virtualworlddigital.com</a>
        </p>
    </div>
</div>
@endsection
