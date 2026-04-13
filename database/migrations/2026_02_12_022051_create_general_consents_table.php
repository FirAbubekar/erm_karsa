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
        Schema::create('general_consents', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 20)->unique();
            $table->string('no_rm', 15);
            $table->string('no_pernyataan', 20);
            $table->date('tgl_pernyataan');
            $table->string('pj_nama', 50);
            $table->string('pj_umur', 10);
            $table->string('pj_hubungan', 20);
            $table->enum('pj_jk', ['LAKI-LAKI', 'PEREMPUAN']);
            $table->string('pj_telp', 20);
            $table->string('pj_ktp', 20);
            $table->string('pj_alamat', 200);
            $table->string('pj_kepercayaan', 100)->nullable();
            $table->string('signature_path');
            $table->string('kd_petugas', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_consents');
    }
};
