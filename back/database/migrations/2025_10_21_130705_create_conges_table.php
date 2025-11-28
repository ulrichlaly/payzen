<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained('collaborators')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nb_jours')->default(0);
            $table->enum('type', ['annuel', 'maladie', 'maternité', 'autre'])->default('annuel');
            $table->enum('statut', ['en_attente', 'approuvé', 'refusé'])->default('en_attente');
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
