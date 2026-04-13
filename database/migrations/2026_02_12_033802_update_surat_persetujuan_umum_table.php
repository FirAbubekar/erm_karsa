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
        // Add signature_path to existing table
        if (Schema::hasTable('surat_persetujuan_umum')) {
            Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
                if (!Schema::hasColumn('surat_persetujuan_umum', 'signature_path')) {
                    $table->string('signature_path', 255)->nullable()->after('nip');
                }
            });
        }

        // Drop the temporary table we created earlier
        Schema::dropIfExists('general_consents');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('surat_persetujuan_umum')) {
            Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
                $table->dropColumn('signature_path');
            });
        }
    }
};
