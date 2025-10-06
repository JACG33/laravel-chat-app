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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');

            $table->id();
            $table->integer('id_chat')->isNotEmpty();
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->longText('mensaje')->isNotEmpty();
            $table->string('tipo_mensaje', 50)->isNotEmpty();
            $table->longText('archivo_mensaje')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
