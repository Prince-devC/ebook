@extends('layouts.app')

@section('title', $ebook->titre)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800">Accueil</a>
        <span class="mx-2 text-gray-500">/</span>
        <a href="{{ route('ebooks.index') }}" class="text-indigo-600 hover:text-indigo-800">Catalogue</a>
        <span class="mx-2 text-gray-500">/</span>
        <span class="text-gray-700">{{ $ebook->titre }}</span>
    </nav>

    <!-- Détails de l'ebook -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
            <!-- Image -->
            <div>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg h-96 flex items-center justify-center">
                    @if($ebook->image)
                        <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="h-full w-full object-cover rounded-lg">
                    @else
                        <span class="text-9xl">📖</span>
                    @endif
                </div>

                <!-- Badges -->
                <div class="flex gap-2 mt-4">
                    @if($ebook->bestseller)
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">🔥 Bestseller</span>
                    @endif
                    @if($ebook->nouveau)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">✨ Nouveau</span>
                    @endif
                </div>
            </div>

            <!-- Informations -->
            <div>
                <span class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium mb-4">
                    {{ $ebook->category->nom }}
                </span>

                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $ebook->titre }}</h1>
                
                <div class="flex items-center text-gray-600 mb-6">
                    <i class="fas fa-user mr-2"></i>
                    <span class="text-lg">Par <strong>{{ $ebook->auteur }}</strong></span>
                </div>

                <!-- Prix -->
                <div class="mb-6">
                    @if($ebook->prix_promo)
                        <div class="flex items-baseline gap-3">
                            <span class="text-4xl font-bold text-green-600">{{ number_format($ebook->prix_promo, 0, ',', ' ') }} FCFA</span>
                            <span class="text-xl text-gray-500 line-through">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                -{{ round((($ebook->prix - $ebook->prix_promo) / $ebook->prix) * 100) }}%
                            </span>
                        </div>
                    @else
                        <span class="text-4xl font-bold text-gray-800">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                    @endif
                </div>

                <!-- Caractéristiques -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600"><i class="fas fa-file-pdf mr-2"></i>Pages</span>
                        <span class="font-semibold">{{ $ebook->pages }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600"><i class="fas fa-language mr-2"></i>Langue</span>
                        <span class="font-semibold">{{ $ebook->langue }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600"><i class="fas fa-eye mr-2"></i>Vues</span>
                        <span class="font-semibold">{{ $ebook->vues }}</span>
                    </div>
                </div>

                <!-- Bouton Ajouter au panier -->
                <form action="{{ route('cart.add', $ebook->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition flex items-center justify-center">
                        <i class="fas fa-cart-plus mr-3"></i>
                        Ajouter au Panier
                    </button>
                </form>

                <!-- Informations complémentaires -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div class="text-sm text-gray-700">
                            <p class="font-semibold mb-1">Livraison instantanée</p>
                            <p>Accès immédiat après paiement. Téléchargez votre ebook en format PDF.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="border-t border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Description</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $ebook->description }}</p>
        </div>
    </div>

    <!-- Ebooks similaires -->
    @if($similaires->count() > 0)
        <div class="mt-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Ebooks Similaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similaires as $similaire)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="h-40 bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            @if($similaire->image)
                                <img src="/storage/{{ $similaire->image }}" alt="{{ $similaire->titre }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-5xl">📖</span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ Str::limit($similaire->titre, 40) }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $similaire->auteur }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                @if($similaire->prix_promo)
                                    <span class="text-lg font-bold text-green-600">{{ number_format($similaire->prix_promo, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="text-lg font-bold text-gray-800">{{ number_format($similaire->prix, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>

                            <a href="{{ route('ebooks.show', $similaire->slug) }}" class="block w-full bg-indigo-600 text-white px-4 py-2 rounded-lg text-center hover:bg-indigo-700 transition">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
