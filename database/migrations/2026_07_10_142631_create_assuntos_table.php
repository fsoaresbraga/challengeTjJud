<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assunto', function (Blueprint $table) {
            $table->increments('cod_assunto');
            $table->string('descricao', 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assunto');
    }
};
