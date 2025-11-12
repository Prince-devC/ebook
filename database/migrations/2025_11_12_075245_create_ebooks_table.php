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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('auteur', 150);
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->decimal('prix_promo', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('fichier_pdf')->nullable();
            $table->integer('pages');
            $table->string('langue', 50)->default('Français');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('bestseller')->default(false);
            $table->boolean('nouveau')->default(false);
            $table->boolean('actif')->default(true);
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
