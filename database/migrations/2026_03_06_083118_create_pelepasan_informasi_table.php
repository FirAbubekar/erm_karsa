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
        Schema::create('pelepasan_informasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekamedis', 15);
            $table->string('nama', 100);
            $table->string('no_telp', 20);
            $table->string('status')->default('aktif');
            $table->dateTime('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelepasan_informasi');
    }
};
