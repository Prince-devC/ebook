<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KkiapayService
{
    protected $publicKey;
    protected $privateKey;
    protected $secret;
    protected $sandbox;

    public function __construct($publicKey, $privateKey, $secret, $sandbox)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->secret = $secret;
        $this->sandbox = $sandbox;
    }

    public function verifyTransaction($transactionId)
    {
        $response = Http::withHeaders([
            'X-API-KEY' => $this->privateKey
        ])->get('https://api.kkiapay.me/api/v1/transactions/' . $transactionId);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la vérification: ' . $response->body());
    }
}
