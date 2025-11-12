<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::where('actif', true)->with('category');
        
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $ebooks = $query->paginate(12);
        $categories = Category::withCount('ebooks')->get();
        
        return view('ebooks.index', compact('ebooks', 'categories'));
    }

    public function show($slug)
    {
        $ebook = Ebook::where('slug', $slug)
            ->where('actif', true)
            ->with('category')
            ->firstOrFail();
        
        // Incrémenter les vues
        $ebook->increment('vues');
        
        // Ebooks similaires (même catégorie)
        $similaires = Ebook::where('actif', true)
            ->where('category_id', $ebook->category_id)
            ->where('id', '!=', $ebook->id)
            ->take(4)
            ->get();
        
        return view('ebooks.show', compact('ebook', 'similaires'));
    }
}
