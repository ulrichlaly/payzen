<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Prêt', 'Avance']);
            $table->decimal('montant', 10, 2);
            $table->integer('duree'); // en mois
            $table->decimal('montant_restant', 10, 2);
            $table->decimal('mensualite', 10, 2);
            $table->text('motif');
            $table->enum('statut', ['En attente', 'Approuvé', 'Rejeté', 'En cours', 'Remboursé'])->default('En attente');
            $table->text('motif_rejet')->nullable();
            $table->date('date_debut')->nullable();
            $table->timestamps();

            // Index pour performance
            $table->index('collaborator_id');
            $table->index('statut');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
