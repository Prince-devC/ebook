<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insérer les valeurs par défaut
        DB::table('payment_settings')->insert([
            ['key' => 'payment_provider', 'value' => 'paydunya', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paydunya_master_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paydunya_private_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paydunya_token', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paydunya_mode', 'value' => 'test', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'fedapay_public_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'fedapay_secret_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'fedapay_mode', 'value' => 'test', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'kkiapay_public_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'kkiapay_private_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'kkiapay_secret', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'kkiapay_sandbox', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
