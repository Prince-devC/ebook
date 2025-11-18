<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Virtual World Digital') - Marketplace d'Ebooks</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book-open text-white text-lg"></i>
                    </div>
                    <a href="{{ route('home') }}" class="flex flex-col">
                        <span class="text-xl font-bold gradient-text">Virtual World</span>
                        <span class="text-xs text-gray-500 -mt-1">Digital Library</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Accueil</a>
                    <a href="{{ route('ebooks.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Catalogue</a>
                    
                    <a href="{{ route('cart.index') }}" class="relative group">
                        <button class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="font-medium">Panier</span>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center animate-pulse">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </button>
                    </a>
                </div>

                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-20 right-0 w-full bg-white shadow-xl py-6 px-4 space-y-4 border-t">
                        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-indigo-600 font-medium py-2">Accueil</a>
                        <a href="{{ route('ebooks.index') }}" class="block text-gray-700 hover:text-indigo-600 font-medium py-2">Catalogue</a>
                        <a href="{{ route('cart.index') }}" class="block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 rounded-xl text-center font-medium">
                            Panier @if(session('cart') && count(session('cart')) > 0)({{ count(session('cart')) }})@endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex justify-between items-center">
                    <p class="flex items-center"><i class="fas fa-check-circle mr-3 text-green-500"></i><span class="font-medium">{{ session('success') }}</span></p>
                    <button @click="show = false" class="text-green-600 hover:text-green-800 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex justify-between items-center">
                    <p class="flex items-center"><i class="fas fa-exclamation-circle mr-3 text-red-500"></i><span class="font-medium">{{ session('error') }}</span></p>
                    <button @click="show = false" class="text-red-600 hover:text-red-800 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Virtual World</h3>
                            <p class="text-sm text-gray-400">Digital Library</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6">Votre marketplace d'ebooks premium. Découvrez des milliers de livres numériques de qualité à prix accessibles.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Navigation</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Accueil</a></li>
                        <li><a href="{{ route('ebooks.index') }}" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Catalogue</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Panier</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Contact</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start"><i class="fas fa-envelope mt-1 mr-3 text-indigo-500"></i><span>contact@virtualworld.com</span></li>
                        <li class="flex items-start"><i class="fas fa-phone mt-1 mr-3 text-indigo-500"></i><span>+229 XX XX XX XX</span></li>
                        <li class="flex items-start"><i class="fas fa-coins mt-1 mr-3 text-indigo-500"></i><span class="font-semibold text-green-400">Paiement en FCFA</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Virtual World Digital. Tous droits réservés.</p>
                <p class="text-gray-500 text-sm mt-2">Propulsé par Laravel & Paydunya</p>
            </div>
        </div>
    </footer>
</body>
</html>
