@extends('admin.layout')

@section('title', 'Ebooks')
@section('header', 'Gestion des Ebooks')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-600">{{ $ebooks->total() }} ebook(s) au total</p>
    </div>
    <a href="{{ route('admin.ebooks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Ajouter un ebook
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auteur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($ebooks as $ebook)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    @if($ebook->image)
                        <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-12 h-16 object-cover rounded">
                    @else
                        <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $ebook->titre }}</div>
                    <div class="text-xs text-gray-500">{{ $ebook->pages }} pages</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $ebook->auteur }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $ebook->category->nom ?? '-' }}</td>
                <td class="px-6 py-4">
                    @if($ebook->prix_promo)
                        <div class="text-sm font-medium text-green-600">{{ number_format($ebook->prix_promo, 0) }} FCFA</div>
                        <div class="text-xs text-gray-400 line-through">{{ number_format($ebook->prix, 0) }} FCFA</div>
                    @else
                        <div class="text-sm font-medium text-gray-900">{{ number_format($ebook->prix, 0) }} FCFA</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-1">
                        @if($ebook->actif)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactif</span>
                        @endif
                        @if($ebook->bestseller)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Best</span>
                        @endif
                        @if($ebook->nouveau)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">New</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.ebooks.edit', $ebook->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.ebooks.destroy', $ebook->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    Aucun ebook trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $ebooks->links() }}
</div>
@endsection
