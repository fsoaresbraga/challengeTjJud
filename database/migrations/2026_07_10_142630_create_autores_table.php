<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autor', function (Blueprint $table) {
            $table->increments('cod_autor');
            $table->string('nome', 40);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autor');
    }
};
