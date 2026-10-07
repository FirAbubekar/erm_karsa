<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            [
                'name'      => 'Skrining Gizi',
                'slug'      => 'gizi.skrining',
                'group'     => 'Instalasi Gizi',
                'deskripsi' => 'Akses input dan riwayat Skrining Gizi (Dewasa, Anak, Lansia)',
            ],
            [
                'name'      => 'Asuhan Gizi',
                'slug'      => 'gizi.asuhan',
                'group'     => 'Instalasi Gizi',
                'deskripsi' => 'Akses input dan riwayat Asuhan Gizi (ADIME)',
            ],
        ];

        foreach ($permissions as $p) {
            $existing = DB::table('web_permissions')->where('slug', $p['slug'])->first();

            if ($existing) {
                $id = $existing->id;
            } else {
                $id = DB::table('web_permissions')->insertGetId(array_merge($p, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            // Berikan akses default ke role Nutrisionis (J020) dan Dokter Gizi (J035)
            foreach (['J020', 'J035'] as $role) {
                $granted = DB::table('web_role_permissions')
                    ->where('role_id', $role)
                    ->where('permission_id', (string) $id)
                    ->exists();

                if (!$granted) {
                    DB::table('web_role_permissions')->insert([
                        'role_id'      => $role,
                        'permission_id' => (string) $id,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $ids = DB::table('web_permissions')
            ->whereIn('slug', ['gizi.skrining', 'gizi.asuhan'])
            ->pluck('id')
            ->map(fn ($v) => (string) $v)
            ->toArray();

        if ($ids) {
            DB::table('web_role_permissions')->whereIn('permission_id', $ids)->delete();
        }

        DB::table('web_permissions')->whereIn('slug', ['gizi.skrining', 'gizi.asuhan'])->delete();
    }
};
