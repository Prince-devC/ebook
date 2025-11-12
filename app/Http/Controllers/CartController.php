<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $ebooks = [];
        $total = 0;

        foreach ($cart as $ebookId) {
            $ebook = Ebook::find($ebookId);
            if ($ebook && $ebook->actif) {
                $ebooks[] = $ebook;
                $total += $ebook->prix_final;
            }
        }

        return view('cart.index', compact('ebooks', 'total'));
    }

    public function add(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);
        
        if (!$ebook->actif) {
            return redirect()->back()->with('error', 'Cet ebook n\'est pas disponible.');
        }

        $cart = session()->get('cart', []);

        // Vérifier si l'ebook est déjà dans le panier
        if (!in_array($id, $cart)) {
            $cart[] = $id;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Ebook ajouté au panier !');
        }

        return redirect()->back()->with('info', 'Cet ebook est déjà dans votre panier.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (($key = array_search($id, $cart)) !== false) {
            unset($cart[$key]);
            session()->put('cart', array_values($cart));
            return redirect()->back()->with('success', 'Ebook retiré du panier.');
        }

        return redirect()->back()->with('error', 'Ebook non trouvé dans le panier.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Panier vidé.');
    }
}
