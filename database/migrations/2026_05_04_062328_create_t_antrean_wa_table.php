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
        Schema::create('t_antrean_wa', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 50)->nullable()->comment('Nomor surat/registrasi referensi');
            $table->string('no_telp', 20)->comment('Nomor WhatsApp tujuan');
            $table->text('pesan')->nullable()->comment('Isi teks pesan WA');
            $table->string('file_path')->nullable()->comment('Path file dokumen (misal PDF) jika ada lampiran');
            $table->enum('status', ['pending', 'processing', 'sent', 'failed'])->default('pending')->comment('Status pengiriman');
            $table->text('error_message')->nullable()->comment('Pesan error jika pengiriman gagal');
            $table->timestamp('sent_at')->nullable()->comment('Waktu pesan berhasil dikirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_antrean_wa');
    }
};
