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
            $columns = [
                'auth_name_1', 'auth_telp_1',
                'auth_name_2', 'auth_telp_2',
                'auth_name_3', 'auth_telp_3',
                'auth_name_4', 'auth_telp_4'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('surat_persetujuan_umum', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_persetujuan_umum', function (Blueprint $table) {
            $table->string('auth_name_1', 100)->nullable()->after('no_telp');
            $table->string('auth_telp_1', 20)->nullable()->after('auth_name_1');
            $table->string('auth_name_2', 100)->nullable()->after('auth_telp_1');
            $table->string('auth_telp_2', 20)->nullable()->after('auth_name_2');
            $table->string('auth_name_3', 100)->nullable()->after('auth_telp_2');
            $table->string('auth_telp_3', 20)->nullable()->after('auth_name_3');
            $table->string('auth_name_4', 100)->nullable()->after('auth_telp_3');
            $table->string('auth_telp_4', 20)->nullable()->after('auth_name_4');
        });
    }
};
