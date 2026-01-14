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
        Schema::create('confirmacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('idade');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->enum('presenca', ['sim', 'nao']);
            $table->boolean('tem_parceiro')->default(false);
            $table->boolean('tem_filhos')->default(false);
            $table->text('restricoes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('presenca');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmacoes');
    }
};
