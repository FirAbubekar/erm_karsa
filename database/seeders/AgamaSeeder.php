<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Agama;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agama::updateOrCreate(
            ['id' => '018d451c-0000-77a0-8800-2f3b7c7d9a10'],
            [
                'kode' => 'IS',
                'nama' => 'ISLAM',
                'status' => 'AKTIF',
            ]
        );

        Agama::updateOrCreate(
            ['id' => '018d451c-0000-77a0-8800-2f3b7c7d9a11'],
            [
                'kode' => 'KR',
                'nama' => 'KRISTEN',
                'status' => 'AKTIF',
            ]
        );
    }
}
