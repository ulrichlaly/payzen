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
        Schema::create('paies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained('collaborators')->onDelete('cascade');
            $table->string('mois');
            $table->year('annee');
            $table->decimal('salaire_base', 10, 2);
            $table->decimal('prime', 10, 2)->default(0);
            $table->decimal('indemnite', 10, 2)->default(0);
            $table->decimal('retenue', 10, 2)->default(0);
            $table->decimal('net_a_payer', 10, 2)->default(0);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paies');
    }
};
