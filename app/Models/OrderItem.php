<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'ebook_id',
        'titre_ebook',
        'prix',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}
