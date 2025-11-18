<?php

namespace App\Services;

use FedaPay\FedaPay;
use FedaPay\Transaction;

class FedaPayService
{
    public function __construct($publicKey, $secretKey, $mode)
    {
        FedaPay::setApiKey($secretKey);
        FedaPay::setEnvironment($mode);
    }

    public function createTransaction(array $data)
    {
        return Transaction::create([
            'description' => $data['description'],
            'amount' => $data['amount'],
            'currency' => ['iso' => $data['currency'] ?? 'XOF'],
            'callback_url' => $data['callback_url'],
            'customer' => [
                'firstname' => $data['customer']['firstname'],
                'lastname' => $data['customer']['lastname'],
                'email' => $data['customer']['email'],
                'phone_number' => $data['customer']['phone_number'] ?? null,
            ]
        ]);
    }

    public function getTransaction($transactionId)
    {
        return Transaction::retrieve($transactionId);
    }
}
