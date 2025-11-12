@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">📚 Bienvenue sur Virtual World Digital</h1>
            <p class="text-xl md:text-2xl mb-8">Découvrez notre collection d'ebooks de qualité</p>
            <a href="{{ route('ebooks.index') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Découvrir le Catalogue
            </a>
        </div>
    </div>
</div>

<!-- Catégories -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Nos Catégories</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('ebooks.index', ['category' => $category->id]) }}" 
               class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition text-center">
                <h3 class="font-bold text-gray-800 mb-2">{{ $category->nom }}</h3>
                <p class="text-sm text-gray-600">{{ $category->ebooks_count }} ebooks</p>
            </a>
        @endforeach
    </div>
</div>

<!-- Bestsellers -->
@if($bestsellers->count() > 0)
<div class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🔥 Bestsellers</h2>
            <a href="{{ route('ebooks.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($bestsellers as $ebook)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                        @if($ebook->image)
                            <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-6xl">📖</span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ $ebook->category->nom }}</span>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">🔥 Bestseller</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $ebook->titre }}</h3>
                        <p class="text-gray-600 text-sm mb-2">Par {{ $ebook->auteur }}</p>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($ebook->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($ebook->prix_promo)
                                    <span class="text-2xl font-bold text-green-600">{{ number_format($ebook->prix_promo, 0, ',', ' ') }} FCFA</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="text-2xl font-bold text-gray-800">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('ebooks.show', $ebook->slug) }}" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-center hover:bg-gray-300 transition">
                                Détails
                            </a>
                            <form action="{{ route('cart.add', $ebook->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    <i class="fas fa-cart-plus mr-1"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Nouveautés -->
@if($nouveautes->count() > 0)
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-800">✨ Nouveautés</h2>
            <a href="{{ route('ebooks.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($nouveautes as $ebook)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        @if($ebook->image)
                            <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-6xl">📖</span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">{{ $ebook->category->nom }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">✨ Nouveau</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $ebook->titre }}</h3>
                        <p class="text-gray-600 text-sm mb-2">Par {{ $ebook->auteur }}</p>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($ebook->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($ebook->prix_promo)
                                    <span class="text-2xl font-bold text-green-600">{{ number_format($ebook->prix_promo, 0, ',', ' ') }} FCFA</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="text-2xl font-bold text-gray-800">{{ number_format($ebook->prix, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('ebooks.show', $ebook->slug) }}" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-center hover:bg-gray-300 transition">
                                Détails
                            </a>
                            <form action="{{ route('cart.add', $ebook->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    <i class="fas fa-cart-plus mr-1"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Call to Action -->
<div class="bg-indigo-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Prêt à commencer ?</h2>
        <p class="text-xl mb-8">Parcourez notre catalogue complet et trouvez votre prochain livre préféré</p>
        <a href="{{ route('ebooks.index') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Voir le Catalogue Complet
        </a>
    </div>
</div>
@endsection
