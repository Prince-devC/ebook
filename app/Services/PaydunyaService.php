<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaydunyaService
{
    protected $masterKey;
    protected $privateKey;
    protected $token;
    protected $baseUrl;

    public function __construct($masterKey, $privateKey, $token, $mode)
    {
        $this->masterKey = $masterKey;
        $this->privateKey = $privateKey;
        $this->token = $token;
        $this->baseUrl = $mode === 'live' 
            ? 'https://app.paydunya.com/api/v1' 
            : 'https://app.paydunya.com/sandbox-api/v1';
    }

    public function createInvoice($data)
    {
        $response = Http::withHeaders([
            'PAYDUNYA-MASTER-KEY' => $this->masterKey,
            'PAYDUNYA-PRIVATE-KEY' => $this->privateKey,
            'PAYDUNYA-TOKEN' => $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/checkout-invoice/create', [
            'invoice' => [
                'total_amount' => $data['amount'],
                'description' => $data['description'],
            ],
            'store' => [
                'name' => config('app.name'),
            ],
            'actions' => [
                'cancel_url' => $data['cancel_url'],
                'return_url' => $data['return_url'],
            ],
            'custom_data' => $data['custom_data'] ?? [],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la création de la facture: ' . $response->body());
    }

    public function confirmInvoice($token)
    {
        $response = Http::withHeaders([
            'PAYDUNYA-MASTER-KEY' => $this->masterKey,
            'PAYDUNYA-PRIVATE-KEY' => $this->privateKey,
            'PAYDUNYA-TOKEN' => $this->token,
        ])->get($this->baseUrl . '/checkout-invoice/confirm/' . $token);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la confirmation: ' . $response->body());
    }
}
