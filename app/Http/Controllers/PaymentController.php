<?php

namespace App\Http\Controllers;

use App\Services\FedaPayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $fedapay;

    public function __construct(FedaPayService $fedapay)
    {
        $this->fedapay = $fedapay;
    }

    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'description' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'nullable|string',
        ]);

        try {
            $transaction = $this->fedapay->createTransaction([
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'currency' => 'XOF',
                'callback_url' => route('payment.callback'),
                'customer' => [
                    'firstname' => $validated['firstname'],
                    'lastname' => $validated['lastname'],
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone_number'] ?? null,
                ]
            ]);

            return response()->json([
                'success' => true,
                'token' => $transaction->token,
                'url' => $transaction->url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $transactionId = $request->input('id');
        
        try {
            $transaction = $this->fedapay->getTransaction($transactionId);
            
            // Traiter le paiement selon le statut
            if ($transaction->status === 'approved') {
                // Paiement réussi
                return redirect()->route('payment.success');
            }
            
            return redirect()->route('payment.failed');
        } catch (\Exception $e) {
            return redirect()->route('payment.failed');
        }
    }
}
