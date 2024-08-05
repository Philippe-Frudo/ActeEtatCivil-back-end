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
        Schema::create('personne', function (Blueprint $table) {
            $table->id('id_person');
            $table->string('nom_person', 30)->nullable(false);
            $table->string('prenom_person', 50);
            $table->string('sexe_person', 1)->nullable(false);
            $table->string('adrs_person')->nullable(false);

            $table->string('nom_m', 30)->nullable(false);
            $table->string('prenom_m', 50)->nullable(false);
            $table->date('date_nais_m')->nullable(false);
            $table->string('lieu_nais_m', 100)->nullable(false);
            $table->string('age_m', 5)->nullable(true);
            $table->string('profession_m');

            $table->string('adrs_m')->nullable(false);
            $table->string('nom_p')->nullable(false);
            $table->string('prenom_p')->nullable(false);
            $table->date('date_nais_p')->nullable(false);
            $table->string('lieu_nais_p')->nullable(false);
            $table->string('age_p');
            $table->string('profession_p')->nullable(false);
            $table->string('adrs_p')->nullable(false);
            $table->unsignedBigInteger('id_travail')->nullable(false);

            $table->timestamps();

            $table->foreign('id_travail')->references('id_travail')->on('travail')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personne');
    }
};
