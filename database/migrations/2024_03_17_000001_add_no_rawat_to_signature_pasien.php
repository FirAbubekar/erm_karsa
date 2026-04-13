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
        Schema::table('signature_pasien', function (Blueprint $table) {
            $table->string('no_rawat', 20)->nullable()->after('no_rekamedis');
            $table->index('no_rawat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signature_pasien', function (Blueprint $table) {
            $table->dropColumn('no_rawat');
        });
    }
};
