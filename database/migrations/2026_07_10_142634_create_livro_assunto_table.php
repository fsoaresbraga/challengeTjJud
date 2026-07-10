<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->unsignedInteger('livro_id');
            $table->unsignedInteger('assunto_id');

            $table->primary(['livro_id', 'assunto_id']);

            $table->foreign('livro_id')
                ->references('cod_livro')
                ->on('livro')
                ->onDelete('cascade');

            $table->foreign('assunto_id')
                ->references('cod_assunto')
                ->on('assunto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};
