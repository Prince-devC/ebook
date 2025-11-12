<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $fillable = [
        'titre',
        'slug',
        'auteur',
        'description',
        'prix',
        'prix_promo',
        'image',
        'fichier_pdf',
        'pages',
        'langue',
        'category_id',
        'bestseller',
        'nouveau',
        'actif',
        'vues',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'prix_promo' => 'decimal:2',
        'bestseller' => 'boolean',
        'nouveau' => 'boolean',
        'actif' => 'boolean',
        'vues' => 'integer',
        'pages' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPrixFinalAttribute()
    {
        return $this->prix_promo ?? $this->prix;
    }
}
