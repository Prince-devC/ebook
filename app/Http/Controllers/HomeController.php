<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('ebooks')->get();
        $bestsellers = Ebook::where('actif', true)
            ->where('bestseller', true)
            ->with('category')
            ->take(6)
            ->get();
        $nouveautes = Ebook::where('actif', true)
            ->where('nouveau', true)
            ->with('category')
            ->take(6)
            ->get();

        return view('home', compact('categories', 'bestsellers', 'nouveautes'));
    }
}
