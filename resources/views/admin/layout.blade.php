<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">📚 Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.ebooks') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.ebooks*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-book mr-2"></i> Ebooks
                </a>
                <a href="{{ route('admin.categories') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.categories*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-folder mr-2"></i> Catégories
                </a>
                <a href="{{ route('admin.orders') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.orders*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-shopping-cart mr-2"></i> Commandes
                </a>
                <a href="{{ route('admin.payment-settings') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.payment-settings*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-credit-card mr-2"></i> Paiements
                </a>
                <hr class="my-4 border-gray-700">
                <a href="{{ route('home') }}" class="block px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-home mr-2"></i> Voir le site
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-700 text-red-400">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
