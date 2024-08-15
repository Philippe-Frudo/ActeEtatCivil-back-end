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
        Schema::create('officier', function (Blueprint $table) {
            $table->id('id_off');
            $table->string('nom_off', 30)->nullable(false);
            $table->string('prenom_off', 30)->nullable(true);
            $table->string('sexe_off', 1)->nullable(false);
            $table->string('email_off', 100)->unique()->nullable(false);
            $table->string('motPass_off', 255)->nullable(false);
            $table->boolean('isConnect')->default(false);
            $table->boolean('isDelete')->default(false);
            $table->boolean('isConfirm')->default(false);

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
        Schema::dropIfExists('officier');
    }
};
