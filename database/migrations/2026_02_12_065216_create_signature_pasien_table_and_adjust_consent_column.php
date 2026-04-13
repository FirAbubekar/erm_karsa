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
        // 1. Create signature_pasien table
        Schema::create('signature_pasien', function (Blueprint $table) {
            $table->uuid('id_uuid')->primary();
            $table->string('no_rekamedis', 20);
            $table->string('signature_path', 255);
            $table->index('no_rekamedis');
        });

        // 2. Adjust surat_persetujuan_umum column
        if (Schema::hasTable('surat_persetujuan_umum')) {
            Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
                if (Schema::hasColumn('surat_persetujuan_umum', 'signature_path')) {
                    $table->renameColumn('signature_path', 'signatures_path');
                } elseif (!Schema::hasColumn('surat_persetujuan_umum', 'signatures_path')) {
                    $table->string('signatures_path', 255)->nullable()->after('nip');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_pasien');

        if (Schema::hasTable('surat_persetujuan_umum')) {
            Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
                if (Schema::hasColumn('surat_persetujuan_umum', 'signatures_path')) {
                    $table->renameColumn('signatures_path', 'signature_path');
                }
            });
        }
    }
};
