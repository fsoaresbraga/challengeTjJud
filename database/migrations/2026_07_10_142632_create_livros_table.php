<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livro', function (Blueprint $table) {
            $table->increments('cod_livro');
            $table->string('titulo', 150);
            $table->string('editora', 40);
            $table->integer('edicao');
            $table->unsignedSmallInteger('ano_publicacao');
            $table->decimal('valor', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livro');
    }
};
