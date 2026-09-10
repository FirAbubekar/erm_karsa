<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $gejalaMap = [
        'gejala_demam' => 'Demam',
        'gejala_menggigil' => 'Menggigil',
        'gejala_gatal' => 'Gatal / Urtikaria',
        'gejala_sesak' => 'Sesak Napas',
    ];

    private array $tindakanMap = [
        'tindakan_hentikan' => 'Transfusi dihentikan',
        'tindakan_infus' => 'Jalur infus dipertahankan dengan NaCl 0,9%',
        'tindakan_dokter' => 'Dilaporkan ke dokter',
        'tindakan_bdrs' => 'Dilaporkan ke BDRS/UTD',
        'tindakan_lab' => 'Pemeriksaan laboratorium ulang',
    ];

    private array $spesimenMap = [
        'spesimen_kantong' => 'Kantong darah sisa transfusi',
        'spesimen_selang' => 'Selang transfusi',
        'spesimen_darah' => 'Sampel darah pasien pasca transfusi',
        'spesimen_formulir' => 'Formulir investigasi reaksi transfusi',
        'spesimen_urin' => 'Urin pasien',
    ];

    private array $investigasiMap = [
        'inv_gol_darah' => 'Pemeriksaan Golongan Darah',
        'inv_crossmatch' => 'Crossmatch Ulang',
        'inv_hemolisis' => 'Uji Hemolisis',
        'inv_dat' => 'DAT (Direct Antiglobulin Test)',
        'inv_kultur' => 'Kultur (bila perlu)',
    ];

    private array $oldColumns = [
        'gejala_demam', 'gejala_menggigil', 'gejala_gatal', 'gejala_sesak', 'gejala_lain',
        'tindakan_hentikan', 'tindakan_infus', 'tindakan_dokter', 'tindakan_bdrs', 'tindakan_lab',
        'tindakan_obat', 'tindakan_lain', 'tindakan_catatan',
        'spesimen_kantong', 'spesimen_selang', 'spesimen_darah', 'spesimen_formulir', 'spesimen_urin', 'spesimen_lain',
        'inv_gol_darah', 'inv_crossmatch', 'inv_hemolisis', 'inv_dat', 'inv_kultur',
    ];

    public function up(): void
    {
        $schema = Schema::connection('simbdrs');
        $db = DB::connection('simbdrs');

        // 1. Buat tabel child
        if (!$schema->hasTable('reaksi_transfusi_gejala')) {
            $schema->create('reaksi_transfusi_gejala', function ($table) {
                $table->id();
                $table->unsignedBigInteger('reaksi_transfusi_id');
                $table->string('item', 150);
                $table->text('nilai')->nullable();
                $table->timestamps();
                $table->foreign('reaksi_transfusi_id')
                    ->references('id')->on('reaksi_transfusi')
                    ->onDelete('cascade');
            });
        }

        if (!$schema->hasTable('reaksi_transfusi_tindakan')) {
            $schema->create('reaksi_transfusi_tindakan', function ($table) {
                $table->id();
                $table->unsignedBigInteger('reaksi_transfusi_id');
                $table->string('item', 150);
                $table->text('nilai')->nullable();
                $table->timestamps();
                $table->foreign('reaksi_transfusi_id')
                    ->references('id')->on('reaksi_transfusi')
                    ->onDelete('cascade');
            });
        }

        if (!$schema->hasTable('reaksi_transfusi_spesimen')) {
            $schema->create('reaksi_transfusi_spesimen', function ($table) {
                $table->id();
                $table->unsignedBigInteger('reaksi_transfusi_id');
                $table->string('item', 150);
                $table->text('nilai')->nullable();
                $table->timestamps();
                $table->foreign('reaksi_transfusi_id')
                    ->references('id')->on('reaksi_transfusi')
                    ->onDelete('cascade');
            });
        }

        if (!$schema->hasTable('reaksi_transfusi_investigasi')) {
            $schema->create('reaksi_transfusi_investigasi', function ($table) {
                $table->id();
                $table->unsignedBigInteger('reaksi_transfusi_id');
                $table->string('item', 150);
                $table->text('nilai')->nullable();
                $table->timestamps();
                $table->foreign('reaksi_transfusi_id')
                    ->references('id')->on('reaksi_transfusi')
                    ->onDelete('cascade');
            });
        }

        // 2. Migrasi data existing: Gejala
        $rows = $db->table('reaksi_transfusi')
            ->where(function ($q) {
                $q->where('gejala_demam', 1)
                  ->orWhere('gejala_menggigil', 1)
                  ->orWhere('gejala_gatal', 1)
                  ->orWhere('gejala_sesak', 1)
                  ->orWhereNotNull('gejala_lain');
            })
            ->get(['id', 'gejala_demam', 'gejala_menggigil', 'gejala_gatal', 'gejala_sesak', 'gejala_lain']);

        foreach ($rows as $row) {
            $inserts = [];
            foreach ($this->gejalaMap as $col => $item) {
                if ($row->$col) {
                    $inserts[] = [
                        'reaksi_transfusi_id' => $row->id,
                        'item' => $item,
                        'nilai' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if ($row->gejala_lain) {
                $inserts[] = [
                    'reaksi_transfusi_id' => $row->id,
                    'item' => 'Lain-lain',
                    'nilai' => $row->gejala_lain,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($inserts) {
                $db->table('reaksi_transfusi_gejala')->insert($inserts);
            }
        }

        // 3. Migrasi data existing: Tindakan
        $rows = $db->table('reaksi_transfusi')
            ->where(function ($q) {
                $q->where('tindakan_hentikan', 1)
                  ->orWhere('tindakan_infus', 1)
                  ->orWhere('tindakan_dokter', 1)
                  ->orWhere('tindakan_bdrs', 1)
                  ->orWhere('tindakan_lab', 1)
                  ->orWhereNotNull('tindakan_obat')
                  ->orWhereNotNull('tindakan_lain');
            })
            ->get(array_merge(['id'], array_keys($this->tindakanMap), ['tindakan_obat', 'tindakan_lain']));

        foreach ($rows as $row) {
            $inserts = [];
            foreach ($this->tindakanMap as $col => $item) {
                if ($row->$col) {
                    $inserts[] = [
                        'reaksi_transfusi_id' => $row->id,
                        'item' => $item,
                        'nilai' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if ($row->tindakan_obat) {
                $inserts[] = [
                    'reaksi_transfusi_id' => $row->id,
                    'item' => 'Pemberian obat',
                    'nilai' => $row->tindakan_obat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($row->tindakan_lain) {
                $inserts[] = [
                    'reaksi_transfusi_id' => $row->id,
                    'item' => 'Lain-lain',
                    'nilai' => $row->tindakan_lain,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($inserts) {
                $db->table('reaksi_transfusi_tindakan')->insert($inserts);
            }
        }

        // 4. Migrasi data existing: Spesimen
        $rows = $db->table('reaksi_transfusi')
            ->where(function ($q) {
                $q->where('spesimen_kantong', 1)
                  ->orWhere('spesimen_selang', 1)
                  ->orWhere('spesimen_darah', 1)
                  ->orWhere('spesimen_formulir', 1)
                  ->orWhere('spesimen_urin', 1)
                  ->orWhereNotNull('spesimen_lain');
            })
            ->get(array_merge(['id'], array_keys($this->spesimenMap), ['spesimen_lain']));

        foreach ($rows as $row) {
            $inserts = [];
            foreach ($this->spesimenMap as $col => $item) {
                if ($row->$col) {
                    $inserts[] = [
                        'reaksi_transfusi_id' => $row->id,
                        'item' => $item,
                        'nilai' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if ($row->spesimen_lain) {
                $inserts[] = [
                    'reaksi_transfusi_id' => $row->id,
                    'item' => 'Lain-lain',
                    'nilai' => $row->spesimen_lain,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($inserts) {
                $db->table('reaksi_transfusi_spesimen')->insert($inserts);
            }
        }

        // 5. Migrasi data existing: Investigasi
        $rows = $db->table('reaksi_transfusi')
            ->where(function ($q) {
                foreach ($this->investigasiMap as $col => $item) {
                    $q->orWhereNotNull($col);
                }
            })
            ->get(array_merge(['id'], array_keys($this->investigasiMap)));

        foreach ($rows as $row) {
            $inserts = [];
            foreach ($this->investigasiMap as $col => $item) {
                if ($row->$col !== null && $row->$col !== '') {
                    $inserts[] = [
                        'reaksi_transfusi_id' => $row->id,
                        'item' => $item,
                        'nilai' => $row->$col,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if ($inserts) {
                $db->table('reaksi_transfusi_investigasi')->insert($inserts);
            }
        }

        // 6. Drop kolom lama
        foreach ($this->oldColumns as $col) {
            if ($schema->hasColumn('reaksi_transfusi', $col)) {
                $db->statement("ALTER TABLE reaksi_transfusi DROP COLUMN `{$col}`");
            }
        }
    }

    public function down(): void
    {
        $schema = Schema::connection('simbdrs');
        $db = DB::connection('simbdrs');

        $newCols = [
            ['gejala_demam', 'TINYINT(1) DEFAULT 0'],
            ['gejala_menggigil', 'TINYINT(1) DEFAULT 0'],
            ['gejala_gatal', 'TINYINT(1) DEFAULT 0'],
            ['gejala_sesak', 'TINYINT(1) DEFAULT 0'],
            ['gejala_lain', 'TEXT NULL'],
            ['tindakan_hentikan', 'TINYINT(1) DEFAULT 0'],
            ['tindakan_infus', 'TINYINT(1) DEFAULT 0'],
            ['tindakan_dokter', 'TINYINT(1) DEFAULT 0'],
            ['tindakan_bdrs', 'TINYINT(1) DEFAULT 0'],
            ['tindakan_lab', 'TINYINT(1) DEFAULT 0'],
            ['tindakan_obat', 'VARCHAR(255) NULL'],
            ['tindakan_lain', 'VARCHAR(255) NULL'],
            ['tindakan_catatan', 'TEXT NULL'],
            ['spesimen_kantong', 'TINYINT(1) DEFAULT 0'],
            ['spesimen_selang', 'TINYINT(1) DEFAULT 0'],
            ['spesimen_darah', 'TINYINT(1) DEFAULT 0'],
            ['spesimen_formulir', 'TINYINT(1) DEFAULT 0'],
            ['spesimen_urin', 'TINYINT(1) DEFAULT 0'],
            ['spesimen_lain', 'VARCHAR(255) NULL'],
            ['inv_gol_darah', 'VARCHAR(100) NULL'],
            ['inv_crossmatch', 'VARCHAR(100) NULL'],
            ['inv_hemolisis', 'VARCHAR(100) NULL'],
            ['inv_dat', 'VARCHAR(100) NULL'],
            ['inv_kultur', 'VARCHAR(100) NULL'],
        ];

        foreach ($newCols as [$col, $def]) {
            if (!$schema->hasColumn('reaksi_transfusi', $col)) {
                $db->statement("ALTER TABLE reaksi_transfusi ADD COLUMN `{$col}` {$def}");
            }
        }

        $mapTindakanItemToCol = [
            'Transfusi dihentikan' => 'tindakan_hentikan',
            'Jalur infus dipertahankan dengan NaCl 0,9%' => 'tindakan_infus',
            'Dilaporkan ke dokter' => 'tindakan_dokter',
            'Dilaporkan ke BDRS/UTD' => 'tindakan_bdrs',
            'Pemeriksaan laboratorium ulang' => 'tindakan_lab',
        ];

        // Restore data from child tables
        foreach (['reaksi_transfusi_gejala', 'reaksi_transfusi_tindakan', 'reaksi_transfusi_spesimen', 'reaksi_transfusi_investigasi'] as $table) {
            $childRows = $db->table($table)->get();
            foreach ($childRows as $cr) {
                $pid = $cr->reaksi_transfusi_id;
                $item = $cr->item;
                $nilai = $cr->nilai;

                switch ($table) {
                    case 'reaksi_transfusi_gejala':
                        $col = array_search($item, $this->gejalaMap);
                        if ($col) {
                            $db->statement("UPDATE reaksi_transfusi SET `{$col}` = 1 WHERE id = ?", [$pid]);
                        } elseif ($item === 'Lain-lain' && $nilai) {
                            $db->statement("UPDATE reaksi_transfusi SET gejala_lain = ? WHERE id = ?", [$nilai, $pid]);
                        }
                        break;
                    case 'reaksi_transfusi_tindakan':
                        if (isset($mapTindakanItemToCol[$item])) {
                            $db->statement("UPDATE reaksi_transfusi SET `{$mapTindakanItemToCol[$item]}` = 1 WHERE id = ?", [$pid]);
                        } elseif ($item === 'Pemberian obat' && $nilai) {
                            $db->statement("UPDATE reaksi_transfusi SET tindakan_obat = ? WHERE id = ?", [$nilai, $pid]);
                        } elseif ($item === 'Lain-lain' && $nilai) {
                            $db->statement("UPDATE reaksi_transfusi SET tindakan_lain = ? WHERE id = ?", [$nilai, $pid]);
                        }
                        break;
                    case 'reaksi_transfusi_spesimen':
                        $col = array_search($item, $this->spesimenMap);
                        if ($col) {
                            $db->statement("UPDATE reaksi_transfusi SET `{$col}` = 1 WHERE id = ?", [$pid]);
                        } elseif ($item === 'Lain-lain' && $nilai) {
                            $db->statement("UPDATE reaksi_transfusi SET spesimen_lain = ? WHERE id = ?", [$nilai, $pid]);
                        }
                        break;
                    case 'reaksi_transfusi_investigasi':
                        $col = array_search($item, $this->investigasiMap);
                        if ($col && $nilai) {
                            $db->statement("UPDATE reaksi_transfusi SET `{$col}` = ? WHERE id = ?", [$nilai, $pid]);
                        }
                        break;
                }
            }
        }

        // Drop child tables
        foreach (['reaksi_transfusi_investigasi', 'reaksi_transfusi_spesimen', 'reaksi_transfusi_tindakan', 'reaksi_transfusi_gejala'] as $table) {
            if ($schema->hasTable($table)) {
                $schema->drop($table);
            }
        }
    }
};
