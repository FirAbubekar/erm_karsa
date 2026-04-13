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
        Schema::table('pelepasan_informasi', function (Blueprint $table) {
            $table->string('no_surat', 20)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelepasan_informasi', function (Blueprint $table) {
            $table->dropColumn('no_surat');
        });
    }
};
