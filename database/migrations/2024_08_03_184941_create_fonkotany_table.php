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
        Schema::create('fonkotany', function (Blueprint $table) {
            $table->id('id_fonkotany');
            $table->string('code_fonkotany', 20)->unique()->nullable(false);
            $table->string('nom_fonkotany', 50)->nullable(false);
            $table->unsignedBigInteger('id_commune')->nullable(false);

            $table->timestamps();

            $table->foreign('id_commune')->references('id_commune')->on('commune')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonkotany');
    }
};
