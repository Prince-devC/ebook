<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'numero_commande',
        'email',
        'nom',
        'prenom',
        'montant_total',
        'statut',
        'methode_paiement',
        'ip_address',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->numero_commande)) {
                $order->numero_commande = 'EB-' . strtoupper(uniqid());
            }
        });
    }
}
