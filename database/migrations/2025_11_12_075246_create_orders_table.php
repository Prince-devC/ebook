<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande', 50)->unique();
            $table->string('email');
            $table->string('nom', 150);
            $table->string('prenom', 150);
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['en_attente', 'payee', 'annulee'])->default('en_attente');
            $table->string('methode_paiement', 50);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
