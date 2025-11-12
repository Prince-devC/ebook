@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">📚 Catalogue d'Ebooks</h1>
        <p class="text-gray-600">Découvrez notre collection complète d'ebooks</p>
    </div>

    <!-- Filtres et Recherche -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <form action="{{ route('ebooks.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Recherche -->
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un ebook, auteur..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Catégorie -->
            <div class="md:w-64">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nom }} ({{ $category->ebooks_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton -->
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
        </form>
    </div>

    <!-- Résultats -->
    <div class="mb-4 text-gray-600">
        {{ $ebooks->total() }} ebook(s) trouvé(s)
    </div>

    <!-- Grille d'ebooks -->
    @if($ebooks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($ebooks as $ebook)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center relative">
                        @if($ebook->image)
                            <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-6xl">📖</span>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-2 right-2 space-y-1">
                            @if($ebook->bestseller)
                                <span class="block text-xs bg-red-500 text-white px-2 py-1 rounded">🔥 Bestseller</span>
                            @endif
                            @if($ebook->nouveau)
                                <span class="block text-xs bg-green-500 text-white px-2 py-1 rounded">✨ Nouveau</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ $ebook->category->nom }}</span>
                        
                        <h3 class="text-lg font-bold text-gray-800 mt-2 mb-1">{{ $ebook->titre }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $ebook->auteur }}</p>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <i class="fas fa-file-pdf mr-1"></i>{{ $ebook->pages }} pages
                            <span class="mx-2">•</span>
                            <i class="fas fa-eye mr-1"></i>{{ $ebook->vues }} vues
                        </div>
                        
                        <div class="mb-3">
                            @if($ebook->prix_promo)
                                <span class="text-xl font-bold text-green-600">{{ number_format($ebook->prix_promo, 0, ',', ' ') }} FCFA</span>
                                <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($ebook->prix, 0, ',', ' ') }}</span>
                            @else
                                <span class="text-xl font-bold text-gray-800">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('ebooks.show', $ebook->slug) }}" class="flex-1 bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-center text-sm hover:bg-gray-300 transition">
                                Détails
                            </a>
                            <form action="{{ route('cart.add', $ebook->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $ebooks->links() }}
        </div>
    @else
        <div class="bg-white p-12 rounded-lg shadow-md text-center">
            <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Aucun ebook trouvé</h3>
            <p class="text-gray-600 mb-6">Essayez avec d'autres critères de recherche</p>
            <a href="{{ route('ebooks.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Réinitialiser les filtres
            </a>
        </div>
    @endif
</div>
@endsection
