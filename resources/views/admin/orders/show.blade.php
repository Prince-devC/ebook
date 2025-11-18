@extends('admin.layout')

@section('title', 'Détail de la commande')
@section('header', 'Commande ' . $order->numero_commande)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations client -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations client</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nom complet</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->prenom }} {{ $order->nom }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Méthode de paiement</p>
                    <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->methode_paiement)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Adresse IP</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->ip_address }}</p>
                </div>
            </div>
        </div>

        <!-- Articles commandés -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Articles commandés</h3>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    @if($item->ebook && $item->ebook->image)
                        <img src="/storage/{{ $item->ebook->image }}" alt="{{ $item->titre_ebook }}" class="w-16 h-20 object-cover rounded">
                    @else
                        <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->titre_ebook }}</h4>
                        @if($item->ebook)
                            <p class="text-sm text-gray-500">{{ $item->ebook->auteur }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ number_format($item->prix, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Résumé -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Résumé</h3>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">N° Commande</span>
                    <span class="font-medium text-gray-900">{{ $order->numero_commande }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Date</span>
                    <span class="font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Statut</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $order->statut === 'payee' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->statut) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mb-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Sous-total</span>
                    <span class="font-medium text-gray-900">{{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Frais</span>
                    <span class="font-medium text-green-600">Gratuit</span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between">
                    <span class="text-lg font-semibold text-gray-900">Total</span>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.orders') }}" class="block w-full text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
