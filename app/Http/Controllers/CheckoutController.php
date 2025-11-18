<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use App\Services\PaydunyaService;
use App\Services\FedaPayService;
use App\Services\KkiapayService;
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

        $provider = PaymentSetting::get('payment_provider', 'paydunya');

        return view('checkout.index', compact('ebooks', 'total', 'provider'));
    }

    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:150',
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Panier vide']);
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

        session()->put('checkout_data', $validated);

        $provider = PaymentSetting::get('payment_provider', 'paydunya');

        try {
            if ($provider === 'paydunya') {
                return $this->initiatePaydunya($validated, $total, count($ebooks));
            } elseif ($provider === 'fedapay') {
                return $this->initiateFedapay($validated, $total, count($ebooks));
            } elseif ($provider === 'kkiapay') {
                return $this->initiateKkiapay($validated, $total);
            }

            return response()->json(['success' => false, 'message' => 'Provider non configuré']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function initiatePaydunya($validated, $total, $count)
    {
        $service = new PaydunyaService(
            PaymentSetting::get('paydunya_master_key'),
            PaymentSetting::get('paydunya_private_key'),
            PaymentSetting::get('paydunya_token'),
            PaymentSetting::get('paydunya_mode', 'test')
        );

        $invoice = $service->createInvoice([
            'amount' => $total,
            'description' => "Achat de {$count} ebook(s)",
            'cancel_url' => route('checkout.index'),
            'return_url' => route('checkout.callback'),
            'custom_data' => $validated,
        ]);

        return response()->json(['success' => true, 'url' => $invoice['response_text']]);
    }

    private function initiateFedapay($validated, $total, $count)
    {
        $service = new FedaPayService(
            PaymentSetting::get('fedapay_public_key'),
            PaymentSetting::get('fedapay_secret_key'),
            PaymentSetting::get('fedapay_mode', 'test')
        );

        $transaction = $service->createTransaction([
            'description' => "Achat de {$count} ebook(s)",
            'amount' => $total,
            'currency' => 'XOF',
            'callback_url' => route('checkout.callback'),
            'customer' => [
                'firstname' => $validated['prenom'],
                'lastname' => $validated['nom'],
                'email' => $validated['email'],
                'phone_number' => $validated['telephone'],
            ]
        ]);

        return response()->json(['success' => true, 'url' => $transaction->url]);
    }

    private function initiateKkiapay($validated, $total)
    {
        return response()->json([
            'success' => true,
            'provider' => 'kkiapay',
            'data' => [
                'amount' => $total,
                'key' => PaymentSetting::get('kkiapay_public_key'),
                'sandbox' => PaymentSetting::get('kkiapay_sandbox') === 'true',
                'phone' => $validated['telephone'],
                'email' => $validated['email'],
                'name' => $validated['prenom'] . ' ' . $validated['nom']
            ]
        ]);
    }

    public function callback(Request $request)
    {
        $provider = PaymentSetting::get('payment_provider', 'paydunya');

        try {
            if ($provider === 'paydunya') {
                return $this->callbackPaydunya($request);
            } elseif ($provider === 'fedapay') {
                return $this->callbackFedapay($request);
            }

            return redirect()->route('checkout.index')->with('error', 'Provider non configuré');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }

    private function callbackPaydunya($request)
    {
        $token = $request->input('token');
        
        if (!$token) {
            return redirect()->route('checkout.index')->with('error', 'Token invalide');
        }

        $service = new PaydunyaService(
            PaymentSetting::get('paydunya_master_key'),
            PaymentSetting::get('paydunya_private_key'),
            PaymentSetting::get('paydunya_token'),
            PaymentSetting::get('paydunya_mode', 'test')
        );

        $invoice = $service->confirmInvoice($token);
        
        if ($invoice['status'] === 'completed') {
            return $this->createOrder($request);
        }

        return redirect()->route('checkout.index')->with('error', 'Paiement non confirmé');
    }

    private function callbackFedapay($request)
    {
        $transactionId = $request->input('id');
        
        $service = new FedaPayService(
            PaymentSetting::get('fedapay_public_key'),
            PaymentSetting::get('fedapay_secret_key'),
            PaymentSetting::get('fedapay_mode', 'test')
        );

        $transaction = $service->getTransaction($transactionId);
        
        if ($transaction->status === 'approved') {
            return $this->createOrder($request);
        }

        return redirect()->route('checkout.index')->with('error', 'Paiement non confirmé');
    }

    public function processKkiapay(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string',
        ]);

        try {
            $service = new KkiapayService(
                PaymentSetting::get('kkiapay_public_key'),
                PaymentSetting::get('kkiapay_private_key'),
                PaymentSetting::get('kkiapay_secret'),
                PaymentSetting::get('kkiapay_sandbox') === 'true'
            );

            $transaction = $service->verifyTransaction($validated['transaction_id']);

            if ($transaction['status'] === 'SUCCESS') {
                $order = $this->createOrder($request);
                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.success', $order->numero_commande)
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Paiement non vérifié']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function createOrder($request)
    {
        $cart = session()->get('cart', []);
        $checkoutData = session()->get('checkout_data');
        
        $total = 0;
        $ebooks = [];

        foreach ($cart as $ebookId) {
            $ebook = Ebook::find($ebookId);
            if ($ebook && $ebook->actif) {
                $ebooks[] = $ebook;
                $total += $ebook->prix_final;
            }
        }

        DB::beginTransaction();

        $order = Order::create([
            'email' => $checkoutData['email'],
            'nom' => $checkoutData['nom'],
            'prenom' => $checkoutData['prenom'],
            'montant_total' => $total,
            'statut' => 'payee',
            'methode_paiement' => 'mobile_money',
            'ip_address' => $request->ip(),
        ]);

        foreach ($ebooks as $ebook) {
            OrderItem::create([
                'order_id' => $order->id,
                'ebook_id' => $ebook->id,
                'titre_ebook' => $ebook->titre,
                'prix' => $ebook->prix_final,
            ]);
        }

        DB::commit();
        session()->forget(['cart', 'checkout_data']);

        return $order;
    }

    public function success($numeroCommande)
    {
        $order = Order::where('numero_commande', $numeroCommande)
            ->with('orderItems.ebook')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
