@extends('admin.layout')

@section('title', 'Modifier un ebook')
@section('header', 'Modifier un ebook')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.ebooks.update', $ebook->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre', $ebook->titre) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('titre')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Auteur -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Auteur *</label>
                <input type="text" name="auteur" value="{{ old('auteur', $ebook->auteur) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('auteur')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select name="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Sélectionner...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $ebook->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->nom }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Prix -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix (FCFA) *</label>
                <input type="number" name="prix" value="{{ old('prix', $ebook->prix) }}" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('prix')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Prix Promo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix Promo (FCFA)</label>
                <input type="number" name="prix_promo" value="{{ old('prix_promo', $ebook->prix_promo) }}" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('prix_promo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Pages -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de pages</label>
                <input type="number" name="pages" value="{{ old('pages', $ebook->pages) }}" min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('pages')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Langue -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                <input type="text" name="langue" value="{{ old('langue', $ebook->langue) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('langue')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $ebook->description) }}</textarea>
                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de couverture</label>
                @if($ebook->image)
                    <img src="/storage/{{ $ebook->image }}" alt="{{ $ebook->titre }}" class="w-32 h-40 object-cover rounded mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Max 2MB - Laisser vide pour conserver l'image actuelle</p>
                @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Fichier PDF -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fichier PDF</label>
                @if($ebook->fichier_pdf)
                    <p class="text-sm text-green-600 mb-2"><i class="fas fa-check-circle"></i> Fichier existant</p>
                @endif
                <input type="file" name="fichier_pdf" accept=".pdf"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Max 10MB - Laisser vide pour conserver le fichier actuel</p>
                @error('fichier_pdf')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Options -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <div class="flex gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', $ebook->actif) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Actif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="bestseller" value="1" {{ old('bestseller', $ebook->bestseller) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Bestseller</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="nouveau" value="1" {{ old('nouveau', $ebook->nouveau) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Nouveau</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
            <a href="{{ route('admin.ebooks') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
