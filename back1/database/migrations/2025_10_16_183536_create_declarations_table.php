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
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paie_id')->constrained('paies')->onDelete('cascade');
            $table->enum('type', ['sociale', 'fiscale']);
            $table->decimal('montant_total', 10, 2);
            $table->date('date_declaration');
            $table->string('reference')->unique()->nullable();
            $table->text('details')->nullable();
            $table->enum('statut', ['en_attente', 'soumise', 'validee', 'rejettee'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};
