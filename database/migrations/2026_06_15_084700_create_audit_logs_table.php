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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->index()->comment('NIP Petugas yang login');
            $table->string('menu', 50)->index()->comment('Nama modul/menu (GC, SPRI, Lab)');
            $table->string('no_rawat', 30)->index()->comment('Referensi nomor rawat pasien');
            $table->string('no_surat', 50)->nullable()->index()->comment('Nomor surat/dokumen terkait');
            $table->enum('aksi', ['CREATE', 'UPDATE', 'DELETE'])->comment('Jenis tindakan');
            $table->json('data_lama')->nullable()->comment('Data sebelum diubah (format JSON)');
            $table->json('data_baru')->nullable()->comment('Data sesudah diubah (format JSON)');
            $table->string('ip_address', 45)->comment('IP address pengakses');
            $table->timestamp('created_at')->useCurrent()->index()->comment('Waktu log dicatat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
