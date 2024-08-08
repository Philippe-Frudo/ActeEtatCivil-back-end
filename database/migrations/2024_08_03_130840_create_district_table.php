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
        Schema::create('district', function (Blueprint $table) {
            
            $table->id('id_district');
            $table->string('code_district', 10)->unique()->nullable(false);
            $table->string('nom_district', 50)->nullable(false);
            $table->unsignedBigInteger('id_region')->nullable(false);

            $table->timestamps();

            // Contrainte de clé étrangère
            $table->foreign('id_region')->references('id_region')->on('region')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};
