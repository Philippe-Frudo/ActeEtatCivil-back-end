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
        Schema::create('commune', function (Blueprint $table) {
            $table->id('id_commune');
            $table->string('code_commune', 10)->unique()->nullable(false);
            $table->string('nom_commune', 50)->nullable(false);
            $table->unsignedBigInteger('id_district')->nullable(false);

            $table->timestamps();

            // Contrainte de clé étrangère
            $table->foreign('id_district')->references('id_district')->on('district')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commune');
    }
};
