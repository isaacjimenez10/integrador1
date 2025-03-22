<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('tipo', 30);
            $table->string('ubicacion', 100);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sensores');
    }
};
