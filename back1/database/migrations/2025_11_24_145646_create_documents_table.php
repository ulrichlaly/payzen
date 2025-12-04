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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collaborator_id')->unsigned();
            $table->string('type')->default('contract'); // contract, autres...
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->string('extension');
            $table->integer('taille');
            $table->text('description')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('statut')->default('actif'); // actif, inactif, expiré
            $table->timestamps();

            // Clés étrangères
            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
