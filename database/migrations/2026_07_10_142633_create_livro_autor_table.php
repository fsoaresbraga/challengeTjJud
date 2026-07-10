<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->unsignedInteger('livro_id');
            $table->unsignedInteger('autor_id');

            $table->primary(['livro_id', 'autor_id']);

            $table->foreign('livro_id')
                ->references('cod_livro')
                ->on('livro')
                ->onDelete('cascade');

            $table->foreign('autor_id')
                ->references('cod_autor')
                ->on('autor')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
