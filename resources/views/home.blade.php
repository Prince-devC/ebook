@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center text-white">
            <div class="inline-block mb-6 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                <i class="fas fa-star text-yellow-300 mr-2"></i>Plus de 1000+ ebooks disponibles
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                Votre Bibliothèque<br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-200 to-pink-200">Numérique Premium</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 text-gray-100 max-w-3xl mx-auto leading-relaxed">
                Découvrez des milliers d'ebooks de qualité. Téléchargement instantané, paiement sécurisé en FCFA.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('ebooks.index') }}" class="inline-flex items-center justify-center bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-xl">
                    <i class="fas fa-book-open mr-3"></i>Explorer le Catalogue
                </a>
                <a href="#categories" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition-all duration-200">
                    <i class="fas fa-th-large mr-3"></i>Voir les Catégories
                </a>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
        </svg>
    </div>
</div>

<!-- Stats Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-200">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-book text-white text-2xl"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">{{ $totalEbooks }}+</h3>
            <p class="text-gray-600 font-medium">Ebooks Disponibles</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-200">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-download text-white text-2xl"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">Instantané</h3>
            <p class="text-gray-600 font-medium">Téléchargement Immédiat</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-200">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">100%</h3>
            <p class="text-gray-600 font-medium">Paiement Sécurisé</p>
        </div>
    </div>
</div>

<!-- Catégories -->
<div id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Explorez par Catégorie</h2>
        <p class="text-gray-600 text-lg">Trouvez exactement ce que vous cherchez</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('ebooks.index', ['category' => $category->id]) }}" 
               class="group bg-white p-6 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 text-center transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition-colors">{{ $category->nom }}</h3>
                <p class="text-sm text-gray-500">{{ $category->ebooks_count }} ebooks</p>
            </a>
        @endforeach
    </div>
</div>

<!-- Bestsellers -->
@if($bestsellers->count() > 0)
<div class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-fire text-orange-500 mr-3"></i>Bestsellers
                </h2>
                <p class="text-gray-600">Les ebooks les plus populaires du moment</p>
            </div>
            <a href="{{ route('ebooks.index') }}" class="hidden md:inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold group">
                Voir tout 
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($bestsellers as $ebook)
                <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-64 bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden">
                        @if($ebook->image)
                            <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <i class="fas fa-book text-white text-6xl opacity-50"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                                <i class="fas fa-star mr-1"></i>Bestseller
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-indigo-600 font-semibold mb-2">{{ $ebook->category->nom ?? 'Non catégorisé' }}</div>
                        <h3 class="font-bold text-xl text-gray-800 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $ebook->titre }}</h3>
                        <p class="text-gray-600 text-sm mb-4">Par {{ $ebook->auteur }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                @if($ebook->prix_promo)
                                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($ebook->prix_promo, 0) }} FCFA</span>
                                    <span class="text-sm text-gray-400 line-through ml-2">{{ number_format($ebook->prix, 0) }} FCFA</span>
                                @else
                                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($ebook->prix, 0) }} FCFA</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('ebooks.show', $ebook->slug) }}" class="mt-4 block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Nouveautés -->
@if($nouveautes->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-sparkles text-yellow-500 mr-3"></i>Nouveautés
            </h2>
            <p class="text-gray-600">Les derniers ebooks ajoutés</p>
        </div>
        <a href="{{ route('ebooks.index') }}" class="hidden md:inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold group">
            Voir tout 
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($nouveautes as $ebook)
            <a href="{{ route('ebooks.show', $ebook->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-48 bg-gradient-to-br from-purple-500 to-pink-500">
                        @if($ebook->image)
                            <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <i class="fas fa-book text-white text-4xl opacity-50"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-400 text-green-900 px-2 py-1 rounded-lg text-xs font-bold">NEW</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $ebook->titre }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $ebook->auteur }}</p>
                        <p class="text-lg font-bold text-indigo-600">{{ number_format($ebook->prix_final, 0) }} FCFA</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- CTA Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Prêt à commencer votre lecture ?</h2>
        <p class="text-xl mb-10 text-gray-100">Rejoignez des milliers de lecteurs satisfaits</p>
        <a href="{{ route('ebooks.index') }}" class="inline-flex items-center bg-white text-indigo-600 px-10 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-2xl">
            <i class="fas fa-rocket mr-3"></i>Découvrir maintenant
        </a>
    </div>
</div>
@endsection
