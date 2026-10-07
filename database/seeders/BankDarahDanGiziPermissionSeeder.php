<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankDarahDanGiziPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Bank Darah
            [
                'name'      => 'Rekomendasi Dokter',
                'slug'      => 'bank-darah.rekomendasi',
                'group'     => 'Bank Darah',
                'deskripsi' => 'Akses modul dan riwayat Rekomendasi Dokter Bank Darah',
            ],
            [
                'name'      => 'Validasi Darah',
                'slug'      => 'bank-darah.validasi',
                'group'     => 'Bank Darah',
                'deskripsi' => 'Akses modul Validasi Darah dan Riwayat Penerimaan Darah',
            ],
            [
                'name'      => 'Reaksi Transfusi',
                'slug'      => 'bank-darah.reaksi',
                'group'     => 'Bank Darah',
                'deskripsi' => 'Akses pencatatan dan monitoring Reaksi Transfusi Darah',
            ],

            // Instalasi Gizi
            [
                'name'      => 'Skrining Gizi',
                'slug'      => 'gizi.skrining',
                'group'     => 'Instalasi Gizi',
                'deskripsi' => 'Akses input dan riwayat Skrining Asuhan Gizi (Dewasa, Anak, Lansia)',
            ],
            [
                'name'      => 'Asuhan Gizi (ADIME)',
                'slug'      => 'gizi.asuhan',
                'group'     => 'Instalasi Gizi',
                'deskripsi' => 'Akses input dan riwayat Asuhan Gizi (ADIME)',
            ],
        ];

        foreach ($permissions as $p) {
            $existing = DB::table('web_permissions')->where('slug', $p['slug'])->first();

            if (!$existing) {
                DB::table('web_permissions')->insert([
                    'name'       => $p['name'],
                    'slug'       => $p['slug'],
                    'group'      => $p['group'],
                    'deskripsi'  => $p['deskripsi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Permission [{$p['slug']}] berhasil ditambahkan.");
            } else {
                $this->command->warn("Permission [{$p['slug']}] sudah ada.");
            }
        }
    }
}
