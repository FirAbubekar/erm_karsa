<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('simbdrs');

        if (!$schema->hasColumn('reaksi_transfusi', 'no_reaksi')) {
            DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi ADD COLUMN no_reaksi VARCHAR(50) NULL AFTER id");
        }
        if (!$schema->hasColumn('reaksi_transfusi', 'no_rawat')) {
            DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi ADD COLUMN no_rawat VARCHAR(50) NULL AFTER no_rekam_medis");
        }
        if (!$schema->hasColumn('reaksi_transfusi', 'petugas_pelapor')) {
            DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi ADD COLUMN petugas_pelapor VARCHAR(150) NULL AFTER nama_petugas_bdrs");
        }
        if (!$schema->hasColumn('reaksi_transfusi', 'tindakan_catatan')) {
            DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi ADD COLUMN tindakan_catatan TEXT NULL AFTER tindakan_lain");
        }

        // ubah tanggal_lahir dari DATE ke VARCHAR supaya bisa simpan teks seperti "1990-01-15 (34 tahun)"
        DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi MODIFY COLUMN tanggal_lahir VARCHAR(100) NULL");
    }

    public function down(): void
    {
        $schema = Schema::connection('simbdrs');

        foreach (['tindakan_catatan', 'petugas_pelapor', 'no_rawat', 'no_reaksi'] as $col) {
            if ($schema->hasColumn('reaksi_transfusi', $col)) {
                DB::connection('simbdrs')->statement("ALTER TABLE reaksi_transfusi DROP COLUMN `{$col}`");
            }
        }
    }
};
