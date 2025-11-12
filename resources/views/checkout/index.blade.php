@extends('layouts.app')

@section('title', 'Commander')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">💳 Finaliser ma commande</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Informations de facturation</h2>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-gray-700 font-medium mb-2">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                            @error('prenom')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-gray-700 font-medium mb-2">Nom *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Vos ebooks seront envoyés à cette adresse</p>
                    </div>

                    <!-- Méthode de paiement -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-medium mb-4">Méthode de paiement *</label>
                        
                        <div class="space-y-3">
                            <!-- Carte bancaire -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="methode_paiement" value="carte" checked class="mr-4 w-5 h-5 text-indigo-600">
                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-800">Carte bancaire</div>
                                        <div class="text-sm text-gray-600">Visa, Mastercard, American Express</div>
                                    </div>
                                    <div class="flex gap-2">
                                        <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                                        <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- PayPal -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="methode_paiement" value="paypal" class="mr-4 w-5 h-5 text-indigo-600">
                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-800">PayPal</div>
                                        <div class="text-sm text-gray-600">Paiement sécurisé via PayPal</div>
                                    </div>
                                    <i class="fab fa-paypal text-3xl text-blue-600"></i>
                                </div>
                            </label>

                            <!-- Mobile Money -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="methode_paiement" value="mobile_money" class="mr-4 w-5 h-5 text-indigo-600">
                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-800">Mobile Money</div>
                                        <div class="text-sm text-gray-600">Orange Money, MTN Money, Moov Money</div>
                                    </div>
                                    <i class="fas fa-mobile-alt text-3xl text-orange-600"></i>
                                </div>
                            </label>
                        </div>

                        @error('methode_paiement')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations de sécurité -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-600 mt-1 mr-3"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-semibold mb-1">Paiement 100% sécurisé</p>
                                <p>Vos informations sont cryptées et protégées. Nous ne stockons pas vos données de paiement.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-lock mr-2"></i>Confirmer et Payer
                    </button>
                </form>
            </div>
        </div>

        <!-- Récapitulatif -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Récapitulatif</h2>
                
                <!-- Liste des ebooks -->
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

                <!-- Total -->
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

                <!-- Avantages -->
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
@endsection
