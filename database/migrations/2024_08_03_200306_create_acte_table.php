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
        Schema::create('acte', function (Blueprint $table) {
            $table->id('id_acte');
            $table->string('num_acte', 100)->nullable(false);
            $table->date('date_acte')->nullable(false);
            $table->time('heure_acte')->nullable(false);
            $table->string('lieu_acte', 100)->nullable(false);
            $table->date('date_enreg')->nullable(false);
            $table->time('heure_enreg')->nullable(false);

            $table->string('nom_temoin', 30);
            $table->string('prenom_temoin', 50);
            $table->string('sexe_temoin', 1);
            $table->date('date_nais_temoin');
            $table->string('lieu_nais_temoin', 100);
            $table->string('age_temoin', 2);
            $table->string('adrs_temoin', 100);
            $table->string('profession_temoin', 50);

            $table->unsignedBigInteger('id_type')->nullable(false);
            $table->unsignedBigInteger('id_person')->nullable(false);
            $table->unsignedBigInteger('id_commune')->nullable(false);
            $table->unsignedBigInteger('id_fonkotany')->nullable(false);
            $table->unsignedBigInteger('id_off')->nullable(false);

            $table->timestamps();

            $table->foreign('id_type')->references('id_type')->on('type')->onDelete('cascade');
            $table->foreign('id_person')->references('id_person')->on('personne')->onDelete('cascade');
            $table->foreign('id_commune')->references('id_commune')->on('commune')->onDelete('cascade');
            $table->foreign('id_fonkotany')->references('id_fonkotany')->on('fonkotany')->onDelete('cascade');
            $table->foreign('id_off')->references('id_off')->on('officier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acte');
    }
};
