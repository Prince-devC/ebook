@extends('layouts.app')

@section('title', 'Panier')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">🛒 Mon Panier</h1>

    @if(count($ebooks) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des ebooks -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($ebooks as $ebook)
                    <div class="bg-white rounded-lg shadow-md p-6 flex items-center gap-6">
                        <!-- Image -->
                        <div class="w-24 h-32 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($ebook->image)
                                <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <span class="text-4xl">📖</span>
                            @endif
                        </div>

                        <!-- Informations -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $ebook->titre }}</h3>
                            <p class="text-gray-600 mb-2">{{ $ebook->auteur }}</p>
                            <span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-sm">
                                {{ $ebook->category->nom }}
                            </span>
                        </div>

                        <!-- Prix et actions -->
                        <div class="text-right">
                            <div class="mb-4">
                                @if($ebook->prix_promo)
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($ebook->prix_promo, 0, ',', ' ') }} FCFA</div>
                                    <div class="text-sm text-gray-500 line-through">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</div>
                                @else
                                    <div class="text-2xl font-bold text-gray-800">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</div>
                                @endif
                            </div>

                            <form action="{{ route('cart.remove', $ebook->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                    <i class="fas fa-trash mr-1"></i>Retirer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Récapitulatif</h2>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex justify-between text-gray-600">
                            <span>Nombre d'articles</span>
                            <span class="font-semibold">{{ count($ebooks) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total</span>
                            <span class="font-semibold">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-bold text-gray-800 mb-6">
                        <span>Total</span>
                        <span class="text-indigo-600">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition mb-3">
                        <i class="fas fa-lock mr-2"></i>Passer la commande
                    </a>

                    <a href="{{ route('ebooks.index') }}" class="block w-full bg-gray-200 text-gray-800 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Continuer mes achats
                    </a>

                    <!-- Vider le panier -->
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-red-600 hover:text-red-800 text-sm transition">
                            <i class="fas fa-trash mr-1"></i>Vider le panier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Panier vide -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre panier est vide</h2>
            <p class="text-gray-600 mb-6">Découvrez notre catalogue et ajoutez vos ebooks préférés</p>
            <a href="{{ route('ebooks.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-book mr-2"></i>Découvrir le catalogue
            </a>
        </div>
    @endif
</div>
@endsection
