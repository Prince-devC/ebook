<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Virtual World Digital') - Marketplace d'Ebooks</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="text-3xl">📚</span>
                        <span class="text-xl font-bold text-indigo-600">Virtual World Digital</span>
                    </a>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Accueil</a>
                    <a href="{{ route('ebooks.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Catalogue</a>
                    
                    <!-- Panier -->
                    <a href="{{ route('cart.index') }}" class="relative">
                        <button class="flex items-center space-x-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Panier</span>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </button>
                    </a>
                </div>

                <!-- Menu Mobile Button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <!-- Menu Mobile Dropdown -->
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute top-16 right-0 w-full bg-white shadow-lg py-4 px-4 space-y-4">
                        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-indigo-600 font-medium">Accueil</a>
                        <a href="{{ route('ebooks.index') }}" class="block text-gray-700 hover:text-indigo-600 font-medium">Catalogue</a>
                        <a href="{{ route('cart.index') }}" class="block text-gray-700 hover:text-indigo-600 font-medium">
                            Panier @if(session('cart') && count(session('cart')) > 0)({{ count(session('cart')) }})@endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <p><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded" x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <p><i class="fas fa-info-circle mr-2"></i>{{ session('info') }}</p>
                    <button @click="show = false" class="text-blue-700 hover:text-blue-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Contenu Principal -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- À propos -->
                <div>
                    <h3 class="text-lg font-bold mb-4">📚 Virtual World Digital</h3>
                    <p class="text-gray-400">Votre marketplace d'ebooks en ligne. Des livres numériques de qualité à prix accessibles.</p>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Liens Rapides</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Accueil</a></li>
                        <li><a href="{{ route('ebooks.index') }}" class="hover:text-white transition">Catalogue</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Panier</a></li>
                        <li><a href="/admin" class="hover:text-white transition">Administration</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>contact@virtualworlddigital.com</li>
                        <li><i class="fas fa-phone mr-2"></i>+33 1 23 45 67 89</li>
                        <li class="pt-4">
                            <span class="text-sm">Devise: <strong class="text-green-400">FCFA</strong></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Virtual World Digital. Tous droits réservés. Développé avec ❤️ et Laravel + Filament.</p>
            </div>
        </div>
    </footer>
</body>
</html>
