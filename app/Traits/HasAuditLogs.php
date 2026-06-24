<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

trait HasAuditLogs
{
    public static function bootHasAuditLogs()
    {
        static::created(function ($model) {
            self::logActivity($model, 'CREATE');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'UPDATE');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'DELETE');
        });
    }

    public static function logActivity($model, string $action)
    {
        try {
            $nip = Session::get('user_id') ?? '-';
            $menu = $model->getTable();
            $noRawat = $model->no_rawat ?? '-';
            $noSurat = $model->no_surat ?? '-';

            // Resolve no_rawat from no_surat if missing
            if ($noRawat === '-') {
                if (!empty($model->no_surat) && $model->no_surat !== '-') {
                    $gc = DB::table('surat_persetujuan_umum')->where('no_surat', $model->no_surat)->first();
                    if ($gc) {
                        $noRawat = $gc->no_rawat;
                    } else {
                        $spri = DB::table('surat_persetujuan_rawat_inap')->where('no_surat', $model->no_surat)->first();
                        if ($spri) {
                            $noRawat = $spri->no_rawat;
                        }
                    }
                }
            }

            // Resolve no_surat from no_rawat if missing
            if ($noSurat === '-') {
                if (!empty($model->no_rawat) && $model->no_rawat !== '-') {
                    $gc = DB::table('surat_persetujuan_umum')->where('no_rawat', $model->no_rawat)->first();
                    if ($gc) {
                        $noSurat = $gc->no_surat;
                    } else {
                        $spri = DB::table('surat_persetujuan_rawat_inap')->where('no_rawat', $model->no_rawat)->first();
                        if ($spri) {
                            $noSurat = $spri->no_surat;
                        }
                    }
                }
            }
            
            $dataLama = null;
            $dataBaru = null;

            if ($action === 'CREATE') {
                $baruArray = $model->getAttributes();
                if ($menu === 'surat_persetujuan_umum') {
                    if (isset($model->new_pelepasan_informasi)) {
                        $baruArray['pelepasan_informasi'] = $model->new_pelepasan_informasi;
                    }
                    if (isset($model->new_signature)) {
                        $baruArray['signature_path'] = $model->new_signature;
                    }
                }
                $dataBaru = json_encode($baruArray);
            } elseif ($action === 'UPDATE') {
                $lamaArray = $model->getOriginal();
                $baruArray = $model->getAttributes();
                if ($menu === 'surat_persetujuan_umum') {
                    if (isset($model->old_pelepasan_informasi)) {
                        $lamaArray['pelepasan_informasi'] = $model->old_pelepasan_informasi;
                    }
                    if (isset($model->new_pelepasan_informasi)) {
                        $baruArray['pelepasan_informasi'] = $model->new_pelepasan_informasi;
                    }
                    if (isset($model->old_signature)) {
                        $lamaArray['signature_path'] = $model->old_signature;
                    }
                    if (isset($model->new_signature)) {
                        $baruArray['signature_path'] = $model->new_signature;
                    }
                }
                $dataLama = json_encode($lamaArray);
                $dataBaru = json_encode($baruArray);
            } elseif ($action === 'DELETE') {
                $lamaArray = $model->getOriginal();
                if ($menu === 'surat_persetujuan_umum') {
                    if (isset($model->old_pelepasan_informasi)) {
                        $lamaArray['pelepasan_informasi'] = $model->old_pelepasan_informasi;
                    }
                    if (isset($model->old_signature)) {
                        $lamaArray['signature_path'] = $model->old_signature;
                    }
                }
                $dataLama = json_encode($lamaArray);
            }

            DB::table('activity_logs')->insert([
                'nip' => $nip,
                'menu' => $menu,
                'no_rawat' => $noRawat,
                'no_surat' => $noSurat,
                'aksi' => $action,
                'data_lama' => $dataLama,
                'data_baru' => $dataBaru,
                'ip_address' => Request::ip() ?? '-',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Failed to write audit log: " . $e->getMessage());
        }
    }
}
