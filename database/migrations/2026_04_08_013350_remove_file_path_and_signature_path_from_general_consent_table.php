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
        Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'signature_path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
            $table->string('file_path')->nullable();
            $table->string('signature_path')->nullable();
        });
    }
};
