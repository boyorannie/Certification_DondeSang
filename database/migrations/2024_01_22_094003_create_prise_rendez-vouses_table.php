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
        Schema::create('prise_rendez-vouses', function (Blueprint $table) {
            $table->id();
            $table->enum('statut',['confirmé','annulé']);
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
        Schema::dropIfExists('prise_rendez-vouses');
    }
};
