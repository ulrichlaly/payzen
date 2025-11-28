<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained()->onDelete('cascade');
            $table->enum('categorie', ['Transport', 'Hébergement', 'Restauration', 'Fournitures', 'Autre']);
            $table->decimal('montant', 10, 2);
            $table->date('date');
            $table->text('description');
            $table->string('justificatif')->nullable();
            $table->enum('statut', ['En attente', 'Approuvée', 'Rejetée', 'Remboursée'])->default('En attente');
            $table->text('motif_rejet')->nullable();
            $table->timestamps();

            // Index pour performance
            $table->index('collaborator_id');
            $table->index('statut');
            $table->index('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
