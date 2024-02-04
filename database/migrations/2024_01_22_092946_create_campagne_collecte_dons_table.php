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
        Schema::create('campagne_collecte_dons', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('lieu');
            $table->enum('statut',['ouverte','complete'])->default('ouverte');
            $table->boolean('is_deleted')->default(false); //prend la valeur 0 par defaut
            $table->unsignedBigInteger('structure_id');
            $table->foreign('structure_id')->references('id')->on('structure_santes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_collecte_dons');
    }
};
