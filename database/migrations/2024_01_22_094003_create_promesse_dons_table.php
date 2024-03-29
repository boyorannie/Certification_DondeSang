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
        Schema::create('promesse_dons', function (Blueprint $table) {
            $table->id();
            $table->enum('statut',['confirmé','annulé','en attente'])->default('en attente');
            $table->unsignedBigInteger('campagne_id');
            $table->unsignedBigInteger('donateur_id');
            $table->foreign('campagne_id')->references('id')->on('campagne_collecte_dons')->onDelete('cascade');
            $table->foreign('donateur_id')->references('id')->on('donateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promesse_dons');
    }
};
