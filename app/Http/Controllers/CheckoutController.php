<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $ebooks = [];
        $total = 0;

        foreach ($cart as $ebookId) {
            $ebook = Ebook::find($ebookId);
            if ($ebook && $ebook->actif) {
                $ebooks[] = $ebook;
                $total += $ebook->prix_final;
            }
        }

        return view('checkout.index', compact('ebooks', 'total'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:150',
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'methode_paiement' => 'required|in:carte,paypal,mobile_money',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = 0;
        $ebooks = [];

        foreach ($cart as $ebookId) {
            $ebook = Ebook::find($ebookId);
            if ($ebook && $ebook->actif) {
                $ebooks[] = $ebook;
                $total += $ebook->prix_final;
            }
        }

        if (empty($ebooks)) {
            return redirect()->route('cart.index')->with('error', 'Aucun ebook valide dans le panier.');
        }

        try {
            DB::beginTransaction();

            // Créer la commande
            $order = Order::create([
                'email' => $validated['email'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'montant_total' => $total,
                'statut' => 'payee', // Simulation de paiement réussi
                'methode_paiement' => $validated['methode_paiement'],
                'ip_address' => $request->ip(),
            ]);

            // Créer les items de commande
            foreach ($ebooks as $ebook) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ebook_id' => $ebook->id,
                    'titre_ebook' => $ebook->titre,
                    'prix' => $ebook->prix_final,
                ]);
            }

            DB::commit();

            // Vider le panier
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->numero_commande);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement de votre commande.');
        }
    }

    public function success($numeroCommande)
    {
        $order = Order::where('numero_commande', $numeroCommande)
            ->with('orderItems.ebook')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
