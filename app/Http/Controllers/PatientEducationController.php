<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PatientEducationAssessment;
use App\Models\PatientEducationImplementation;
use App\Models\Pegawai;
use App\Models\Bangsal;

class PatientEducationController extends Controller
{
    /**
     * Tampilkan halaman form RM10 Edukasi Pasien.
     */
    public function index()
    {
        // --- Rencana Kebutuhan Edukasi (Section B checkboxes) ---
        $rencanaKebutuhan = [
            ['kode' => 'B_HAK_PARTISIPASI',   'deskripsi' => 'Hak untuk Berpartisipasi pada Proses Pelayanan'],
            ['kode' => 'B_INFORMED_CONSENT',   'deskripsi' => 'Proses Pemberian Informed Consent'],
            ['kode' => 'B_ALAT_MEDIS',         'deskripsi' => 'Penggunaan Alat Medis yang Aman'],
            ['kode' => 'B_CUCI_TANGAN',        'deskripsi' => 'Cuci Tangan yang Benar'],
            ['kode' => 'B_RUJUKAN',            'deskripsi' => 'Rujukan Edukasi'],
            ['kode' => 'B_PROSEDUR_PENUNJANG', 'deskripsi' => 'Prosedur Pemeriksaan Penunjang'],
            ['kode' => 'B_DIET_NUTRISI',       'deskripsi' => 'Diet dan Nutrisi'],
            ['kode' => 'B_MANAJEMEN_NYERI',    'deskripsi' => 'Manajemen Nyeri'],
            ['kode' => 'B_BAHAYA_ROKOK',       'deskripsi' => 'Bahaya Rokok'],
            ['kode' => 'B_EDUKASI_PULANG',     'deskripsi' => 'Edukasi Pasien Pulang'],
            ['kode' => 'B_DIAGNOSIS',          'deskripsi' => 'Kondisi Kesehatan, Diagnosis pasti, dan penatalaksanaannya'],
            ['kode' => 'B_OBAT',               'deskripsi' => 'Penggunaan Obat secara Efektif dan Aman, Efek Samping serta Interaksinya'],
            ['kode' => 'B_REHABILITASI',       'deskripsi' => 'Teknik Rehabilitasi'],
        ];

        // --- Topik Edukasi Bawaan (Section C table rows) ---
        $topikEdukasi = [
            [
                'kode'      => 'TE_HAK_PARTISIPASI',
                'no_urut'   => 1,
                'poli_unit' => 'Pendaftaran',
                'topik'     => "Hak untuk Berpartisipasi Pada Proses Pelayanan",
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_KONDISI',
                'no_urut'   => 2,
                'poli_unit' => 'Dokter',
                'topik'     => "1. Diagnosis\n2. Tanda dan Gejala Penyakit\n3. Penatalaksanaan/ Terapi\n4. Komplikasi yang mungkin terjadi\n5. Prognosa\n(sebutkan)",
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_PROSEDUR_PENUNJANG',
                'no_urut'   => 3,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Prosedur Pemeriksaan Penunjang (sebutkan)',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_INFORMED_CONSENT',
                'no_urut'   => 4,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Proses Pemberian Informed Consent (sebutkan)',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_DIET_NUTRISI',
                'no_urut'   => 5,
                'poli_unit' => 'Gizi',
                'topik'     => 'Diet dan Nutrisi (sebutkan)',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_OBAT',
                'no_urut'   => 6,
                'poli_unit' => 'Farmasi',
                'topik'     => "1. Manfaat Obat yang Diberikan\n2. Efek Samping Obat-obatan yang Diberi\n3. Interaksi Obat dan Makanan",
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_ALAT_MEDIS',
                'no_urut'   => 7,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Penggunaan Alat Medis yang Aman (Sebutkan)',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_MANAJEMEN_NYERI',
                'no_urut'   => 8,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Manajemen Nyeri',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_REHABILITASI',
                'no_urut'   => 9,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Teknik Rehabilitasi',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_CUCI_TANGAN',
                'no_urut'   => 10,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Cuci Tangan yang Benar',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_BAHAYA_ROKOK',
                'no_urut'   => 11,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Bahaya Merokok',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_EDUKASI_PULANG',
                'no_urut'   => 12,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Edukasi Pasien Pulang',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_RUJUKAN',
                'no_urut'   => 13,
                'poli_unit' => 'Rawat Inap',
                'topik'     => 'Rujukan Internal',
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_EDUKASI_UMUM',
                'no_urut'   => 14,
                'poli_unit' => 'Pendaftaran',
                'topik'     => "Edukasi Pasien Umum\nBersedia membayar seluruh biaya perawatan pada kelas perawatan dari awal sampai selesai perawatan.\nTelah diberikan edukasi oleh petugas pendaftaran.\nMenandatangani tanpa paksaan dengan kesadaran penuh.",
                'is_custom' => false,
            ],
            [
                'kode'      => 'TE_SESUAI_KEBUTUHAN',
                'no_urut'   => 15,
                'poli_unit' => '',
                'topik'     => 'Edukasi Sesuai Kebutuhan Pasien, sesuai PPA yang merawat',
                'is_custom' => false,
            ],
        ];

        // --- Pilihan Verifikasi (Section C table checkboxes per row) ---
        $pilihanVerifikasi = ['Tujuan', 'Metode', 'Materi', 'Evaluasi'];

        // --- Info Tenaga / Materi / Metode Edukasi (Section B right panel) ---
        $tujuanEdukasi  = ['Mampu Mengetahui', 'Mau Berpartisipasi', 'Mengambil Keputusan', 'Mendemonstrasikan'];
        $materiEdukasi  = ['Leaflet', 'Lembar balik', 'Alat Peraga'];
        $metodeEdukasi  = ['Penjelasan', 'Diskusi', 'Ceramah', 'Demonstrasi'];
        $evaluasiEdukasi = ['Sudah Mengerti', 'Mampu Menjelaskan', 'Mampu Mendemonstrasikan', 'Perlu Pengulangan'];

        return view('edukasi_pasien', compact(
            'rencanaKebutuhan',
            'topikEdukasi',
            'pilihanVerifikasi',
            'tujuanEdukasi',
            'materiEdukasi',
            'metodeEdukasi',
            'evaluasiEdukasi'
        ));
    }

    /**
     * Cek apakah sudah ada data asesmen untuk no_rawat tertentu.
     */
    public function getAssessment(Request $request)
    {
        $noRawat = $request->query('no_rawat');
        if (!$noRawat) {
            return response()->json(['exists' => false]);
        }

        $assessment = PatientEducationAssessment::where('no_rawat', $noRawat)->first();

        if (!$assessment) {
            return response()->json(['exists' => false]);
        }

        // Load implementations for this assessment
        $implementations = PatientEducationImplementation::where('assessment_id', $assessment->id_uuid)
            ->orderBy('no_urut')
            ->get()
            ->map(function ($impl) {
                return [
                    'id_uuid'       => $impl->id_uuid,
                    'kode_topik'    => $impl->kode_topik,
                    'nama_topik'    => $impl->nama_topik,
                    'poli_unit'     => $impl->poli_unit,
                    'no_urut'       => $impl->no_urut,
                    'is_custom'     => $impl->is_custom,
                    'verifikasi'    => json_decode($impl->verifikasi, true),
                    'ttd_pasien'    => $impl->ttd_pasien,
                    'ttd_edukator'  => $impl->ttd_edukator,
                    'nama_penerima_info' => $impl->nama_penerima_info,
                    'tgl_reedukasi'     => $impl->tgl_reedukasi,
                    'created_at'        => $impl->created_at ? $impl->created_at->format('Y-m-d H:i:s') : null,
                    'tgl_akhir_edukasi' => $impl->tgl_akhir_edukasi ? \Carbon\Carbon::parse($impl->tgl_akhir_edukasi)->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json([
            'exists' => true,
            'data'   => [
                'id_uuid'              => $assessment->id_uuid,
                'no_rawat'             => $assessment->no_rawat,
                'nama_penerima_info'   => $assessment->nama_penerima_info,
                'hubungan_dgn_pasien'  => $assessment->hubungan_dgn_pasien,
                'bahasa'               => json_decode($assessment->bahasa, true),
                'bahasa_lainnya'       => $assessment->bahasa_lainnya,
                'perlu_penerjemah'     => $assessment->perlu_penerjemah,
                'baca_dan_tulis'       => $assessment->baca_dan_tulis,
                'pendidikan'           => $assessment->pendidikan,
                'pendidikan_lainnya'   => $assessment->pendidikan_lainnya,
                'nilai_budaya'         => $assessment->nilai_budaya,
                'gaya_pembelajaran'    => $assessment->gaya_pembelajaran,
                'literasi_kesehatan'   => $assessment->literasi_kesehatan,
                'hambatan_edukasi'     => json_decode($assessment->hambatan_edukasi, true),
                'hambatan_lainnya'     => $assessment->hambatan_lainnya,
                'kesediaan_menerima'   => $assessment->kesediaan_menerima,
                'rencana_kebutuhan'    => json_decode($assessment->rencana_kebutuhan, true),
                'rencana_lainnya'      => $assessment->rencana_lainnya,
                'tanggal_edukasi'      => $assessment->tanggal_edukasi,
                'nama_pasien_wali_ttd' => $assessment->nama_pasien_wali_ttd,
                'ttd_pasien_wali'      => $assessment->ttd_pasien_wali,
                'implementations'      => $implementations,
            ],
        ]);
    }

    /**
     * Simpan / Update data asesmen edukasi pasien (Section A + B + Konfirmasi).
     */
    public function storeAssessment(Request $request)
    {
        $request->validate([
            'no_rawat'           => 'required|string',
            'nama_penerima_info' => 'required|string',
            'signature'          => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            try {
                // Check if updating existing
                $existing = PatientEducationAssessment::where('no_rawat', $request->no_rawat)->first();
                $isUpdate = $existing !== null;

                // ── Process Signature ──
                $signaturePath = $existing ? $existing->ttd_pasien_wali : null;
                if ($request->signature && str_starts_with($request->signature, 'data:image')) {
                    $imageData = $request->signature;
                    $imageData = str_replace('data:image/png;base64,', '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageBinary = base64_decode($imageData);

                    $safeNoRawat = str_replace('/', '_', $request->no_rawat);
                    $directory = "education/{$safeNoRawat}";
                    $filename = 'TTD_ASSESSMENT_' . time() . '.png';
                    $newSignaturePath = "{$directory}/{$filename}";

                    Storage::disk('local')->put($newSignaturePath, $imageBinary);

                    // Hapus file lama jika ada
                    if ($existing && $existing->ttd_pasien_wali && $existing->ttd_pasien_wali !== $newSignaturePath) {
                        if (Storage::disk('local')->exists($existing->ttd_pasien_wali)) {
                            Storage::disk('local')->delete($existing->ttd_pasien_wali);
                        }
                    }
                    $signaturePath = $newSignaturePath;
                }

                if (!$signaturePath) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Tanda tangan pasien/wali harus diisi.',
                    ], 422);
                }

                // ── Save / Update Assessment ──
                $data = [
                    'nama_penerima_info'  => $request->nama_penerima_info,
                    'hubungan_dgn_pasien' => $request->hubungan_dgn_pasien,
                    'bahasa'              => $request->bahasa ? json_encode($request->bahasa) : null,
                    'bahasa_lainnya'      => $request->bahasa_lainnya,
                    'perlu_penerjemah'    => $request->perlu_penerjemah,
                    'baca_dan_tulis'      => $request->baca_dan_tulis,
                    'pendidikan'          => $request->pendidikan,
                    'pendidikan_lainnya'  => $request->pendidikan_lainnya,
                    'nilai_budaya'        => $request->nilai_budaya,
                    'gaya_pembelajaran'   => $request->gaya_pembelajaran,
                    'literasi_kesehatan'  => $request->literasi_kesehatan,
                    'hambatan_edukasi'    => $request->hambatan_edukasi ? json_encode($request->hambatan_edukasi) : null,
                    'hambatan_lainnya'    => $request->hambatan_lainnya,
                    'kesediaan_menerima'  => $request->kesediaan_menerima,
                    'rencana_kebutuhan'   => $request->rencana_kebutuhan ? json_encode($request->rencana_kebutuhan) : null,
                    'rencana_lainnya'     => $request->rencana_lainnya,
                    'tanggal_edukasi'     => $request->tanggal_edukasi,
                    'nama_pasien_wali_ttd' => $request->nama_pasien_wali_ttd,
                    'ttd_pasien_wali'     => $signaturePath,
                    'ip_address_ttd'      => $request->ip(),
                    'created_by'          => Session::get('user_id'),
                ];

                $assessment = PatientEducationAssessment::updateOrCreate(
                    ['no_rawat' => $request->no_rawat],
                    $data
                );

                if ($isUpdate && !$assessment->wasChanged()) {
                    PatientEducationAssessment::logActivity($assessment, 'UPDATE');
                }

                try {
                    $pdfService = app(\App\Services\PdfService::class);
                    $pdfService->generateAndSaveEdukasiPasien($assessment);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal generate PDF edukasi pasien saat store assessment: " . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => $isUpdate
                        ? 'Data asesmen edukasi berhasil diperbarui!'
                        : 'Data asesmen edukasi berhasil disimpan!',
                    'assessment_id' => $assessment->id_uuid,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Gagal menyimpan: ' . $e->getMessage(),
                ], 500);
            }
        });
    }

    /**
     * Simpan / Update satu baris pelaksanaan edukasi (Section C).
     */
    public function storeImplementation(Request $request)
    {
        $request->validate([
            'no_rawat'    => 'required|string',
            'kode_topik'  => 'required|string',
            'nama_topik'  => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            try {
                // Find the assessment for this no_rawat
                $assessment = PatientEducationAssessment::where('no_rawat', $request->no_rawat)->first();
                if (!$assessment) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Simpan data asesmen terlebih dahulu sebelum menyimpan pelaksanaan.',
                    ], 422);
                }

                $safeNoRawat = str_replace('/', '_', $request->no_rawat);
                $directory = "education/{$safeNoRawat}";

                // Process TTD Pasien
                $ttdPasienPath = null;
                $existing = PatientEducationImplementation::where('assessment_id', $assessment->id_uuid)
                    ->where('kode_topik', $request->kode_topik)->first();

                if ($existing) {
                    $ttdPasienPath = $existing->ttd_pasien;
                }
                if ($request->ttd_pasien) {
                    $newTtdPath = null;
                    if (str_starts_with($request->ttd_pasien, 'data:image')) {
                        $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->ttd_pasien);
                        $filename = 'TTD_PASIEN_' . $request->kode_topik . '_' . time() . '.png';
                        $newTtdPath = "{$directory}/{$filename}";
                        Storage::disk('local')->put($newTtdPath, base64_decode($imgData));
                    } elseif (str_starts_with($request->ttd_pasien, '/edukasi-pasien/signature/')) {
                        // Salin file fisik dari Bagian B
                        $sourcePath = str_replace('/edukasi-pasien/signature/', '', $request->ttd_pasien);
                        if (Storage::disk('local')->exists($sourcePath)) {
                            $filename = 'TTD_PASIEN_' . $request->kode_topik . '_COPIED_' . time() . '.png';
                            $newTtdPath = "{$directory}/{$filename}";
                            Storage::disk('local')->copy($sourcePath, $newTtdPath);
                        } else {
                            $newTtdPath = $sourcePath;
                        }
                    } else {
                        $newTtdPath = $request->ttd_pasien;
                    }

                    // Hapus file lama jika ada dan berbeda dengan yang baru
                    if ($newTtdPath && $existing && $existing->ttd_pasien && $existing->ttd_pasien !== $newTtdPath) {
                        if (Storage::disk('local')->exists($existing->ttd_pasien)) {
                            Storage::disk('local')->delete($existing->ttd_pasien);
                        }
                    }
                    $ttdPasienPath = $newTtdPath;
                }

                // Process TTD Edukator
                $ttdEdukatorPath = Session::get('user_id');

                \Illuminate\Support\Facades\Log::info('Store Implementation Payload:', $request->all());

                // Save / Update Implementation row
                $impl = PatientEducationImplementation::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id_uuid,
                        'kode_topik'    => $request->kode_topik,
                    ],
                    [
                        'poli_unit'         => $request->poli_unit,
                        'nama_topik'        => $request->nama_topik,
                        'no_urut'           => $request->no_urut,
                        'is_custom'         => $request->is_custom ?? false,
                        'verifikasi'        => $request->verifikasi ? json_encode($request->verifikasi) : null,
                        'ttd_pasien'        => $ttdPasienPath,
                        'ttd_edukator'      => $ttdEdukatorPath,
                        'ip_address_submit' => $request->ip(),
                        'tgl_reedukasi'     => $request->tgl_reedukasi,
                        'tgl_akhir_edukasi' => $request->tgl_akhir_edukasi,
                        'created_at'        => $request->tgl_edukasi ?? now(),
                        'nama_penerima_info' => $request->nama_penerima_info,
                    ]
                );

                if ($existing && !$impl->wasChanged()) {
                    PatientEducationImplementation::logActivity($impl, 'UPDATE');
                }

                try {
                    $pdfService = app(\App\Services\PdfService::class);
                    $pdfService->generateAndSaveEdukasiPasien($assessment);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal generate PDF edukasi pasien saat store implementation: " . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pelaksanaan edukasi berhasil disimpan!',
                    'id'      => $impl->id_uuid,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Gagal menyimpan: ' . $e->getMessage(),
                ], 500);
            }
        });
    }

    /**
     * Hapus baris pelaksanaan (implementation)
     */
    public function deleteImplementation(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'kode_topik' => 'required|string',
        ]);

        try {
            $assessment = PatientEducationAssessment::where('no_rawat', $request->no_rawat)->first();
            if (!$assessment) {
                return response()->json(['success' => false, 'error' => 'Data asesmen tidak ditemukan.'], 404);
            }

            $impl = PatientEducationImplementation::where('assessment_id', $assessment->id_uuid)
                ->where('kode_topik', $request->kode_topik)
                ->first();

            if ($impl) {
                // Hapus TTD jika ada
                if ($impl->ttd_pasien && Storage::disk('local')->exists($impl->ttd_pasien)) {
                    Storage::disk('local')->delete($impl->ttd_pasien);
                }

                $impl->delete();
                PatientEducationImplementation::logActivity($impl, 'DELETE');
                
                try {
                    $pdfService = app(\App\Services\PdfService::class);
                    $pdfService->generateAndSaveEdukasiPasien($assessment);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal generate PDF edukasi pasien saat delete implementation: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pelaksanaan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Tampilkan halaman riwayat edukasi pasien
     */
    public function history(Request $request)
    {
        $query = PatientEducationAssessment::with(['regPeriksa.pasien', 'regPeriksa.signaturePasien'])
            ->select('patient_education_assessments.*');

        $hasFilters = $request->filled('search') || $request->filled('no_rawat') ||
            $request->filled('person') || $request->filled('bangsal') || $request->filled('start_date') ||
            $request->filled('end_date');

        if (!$hasFilters) {
            $today = now()->toDateString();
            $request->merge(['start_date' => $today, 'end_date' => $today]);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('regPeriksa.pasien', function ($qp) use ($search) {
                    $qp->where('nm_pasien', 'like', "%$search%")
                        ->orWhere('no_rkm_medis', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('no_rawat')) {
            $query->where('patient_education_assessments.no_rawat', 'like', "%" . $request->no_rawat . "%");
        }

        if ($request->filled('person')) {
            $person = $request->person;
            $query->where('patient_education_assessments.nama_penerima_info', 'like', "%$person%");
        }

        if ($request->filled('bangsal')) {
            $bangsalId = $request->bangsal;
            if ($bangsalId === 'IGD') {
                $query->whereIn('patient_education_assessments.no_rawat', function ($q) {
                    $q->select('no_rawat')->from('reg_periksa')
                      ->where('kd_poli', 'IGDK')
                      ->where('status_lanjut', 'Ralan');
                });
            } else {
                $query->whereIn('patient_education_assessments.no_rawat', function ($q) use ($bangsalId) {
                    $q->select('ki1.no_rawat')
                        ->from('kamar_inap as ki1')
                        ->whereIn('ki1.kd_kamar', function ($q2) use ($bangsalId) {
                            $q2->select('kd_kamar')->from('kamar')->where('kd_bangsal', $bangsalId);
                        })
                        ->whereNotExists(function ($q3) {
                            $q3->selectRaw('1')
                                ->from('kamar_inap as ki2')
                                ->whereColumn('ki1.no_rawat', 'ki2.no_rawat')
                                ->where(function ($q4) {
                                    $q4->whereColumn('ki2.tgl_masuk', '>', 'ki1.tgl_masuk')
                                        ->orWhere(function ($q5) {
                                            $q5->whereColumn('ki2.tgl_masuk', '=', 'ki1.tgl_masuk')
                                                ->whereColumn('ki2.jam_masuk', '>', 'ki1.jam_masuk');
                                        });
                                });
                        });
                });
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('patient_education_assessments.tanggal_edukasi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('patient_education_assessments.tanggal_edukasi', '<=', $request->end_date);
        }

        $assessments = $query->orderBy('patient_education_assessments.tanggal_edukasi', 'desc')
            ->orderBy('patient_education_assessments.created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => PatientEducationAssessment::count(),
            'today' => PatientEducationAssessment::whereDate('tanggal_edukasi', now()->toDateString())->count(),
            'month' => PatientEducationAssessment::whereMonth('tanggal_edukasi', now()->month)
                ->whereYear('tanggal_edukasi', now()->year)->count(),
        ];

        $bangsals = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'ASC')->get();

        return view('edukasi_pasien.history', compact('assessments', 'stats', 'bangsals'));
    }

    /**
     * Download laporan PDF riwayat edukasi pasien
     */
    public function downloadPDF(Request $request, $id_uuid)
    {
        $assessment = PatientEducationAssessment::with(['regPeriksa.pasien'])
            ->where('id_uuid', $id_uuid)
            ->firstOrFail();

        $implementations = PatientEducationImplementation::where('assessment_id', $assessment->id_uuid)
            ->orderBy('no_urut')
            ->get();

        $topikEdukasi = [
            ['kode' => 'TE_HAK_PARTISIPASI', 'poli_unit' => 'Pendaftaran', 'topik' => "Hak untuk Berpartisipasi Pada Proses Pelayanan", 'is_custom' => false],
            ['kode' => 'TE_KONDISI', 'poli_unit' => 'Dokter', 'topik' => "1. Diagnosis\n2. Tanda dan Gejala Penyakit\n3. Penatalaksanaan/ Terapi\n4. Komplikasi yang mungkin terjadi\n5. Prognosa", 'is_custom' => false],
            ['kode' => 'TE_PROSEDUR_PENUNJANG', 'poli_unit' => 'Rawat Inap', 'topik' => 'Prosedur Pemeriksaan Penunjang (sebutkan)', 'is_custom' => false],
            ['kode' => 'TE_INFORMED_CONSENT', 'poli_unit' => 'Pendaftaran', 'topik' => 'Proses Pemberian Informed Consent (sebutkan)', 'is_custom' => false],
            ['kode' => 'TE_DIET_NUTRISI', 'poli_unit' => 'Gizi', 'topik' => 'Diet dan Nutrisi (sebutkan)', 'is_custom' => false],
            ['kode' => 'TE_OBAT', 'poli_unit' => 'Farmasi', 'topik' => "1. Manfaat Obat yang Diberikan\n2. Efek Samping Obat-obatan yang Diberi\n3. Interaksi Obat dan Makanan", 'is_custom' => false],
            ['kode' => 'TE_ALAT_MEDIS', 'poli_unit' => 'Rawat Inap', 'topik' => 'Penggunaan Alat Medis yang Aman (Sebutkan)', 'is_custom' => false],
            ['kode' => 'TE_MANAJEMEN_NYERI', 'poli_unit' => 'Rawat Inap', 'topik' => 'Manajemen Nyeri', 'is_custom' => false],
            ['kode' => 'TE_REHABILITASI', 'poli_unit' => 'Rawat Inap', 'topik' => 'Teknik Rehabilitasi', 'is_custom' => false],
            ['kode' => 'TE_CUCI_TANGAN', 'poli_unit' => 'Rawat Inap', 'topik' => 'Cuci Tangan yang Benar', 'is_custom' => false],
            ['kode' => 'TE_BAHAYA_ROKOK', 'poli_unit' => 'Rawat Inap', 'topik' => 'Bahaya Merokok', 'is_custom' => false],
            ['kode' => 'TE_EDUKASI_PULANG', 'poli_unit' => 'Rawat Inap', 'topik' => 'Edukasi Pasien Pulang', 'is_custom' => false],
            ['kode' => 'TE_RUJUKAN', 'poli_unit' => 'Rawat Inap', 'topik' => 'Rujukan Internal', 'is_custom' => false],
            ['kode' => 'TE_EDUKASI_UMUM', 'poli_unit' => 'Pendaftaran', 'topik' => "Edukasi Pasien Umum\nBersedia membayar seluruh biaya perawatan pada kelas perawatan dari awal sampai selesai perawatan.\nTelah diberikan edukasi oleh petugas pendaftaran.\nMenandatangani tanpa paksaan dengan kesadaran penuh.", 'is_custom' => false],
            ['kode' => 'TE_SESUAI_KEBUTUHAN', 'poli_unit' => '', 'topik' => 'Edukasi Sesuai Kebutuhan Pasien, sesuai PPA yang merawat', 'is_custom' => false],
        ];

        $mergedImplementations = [];

        // 1. Process 15 Default Topics
        foreach ($topikEdukasi as $default) {
            $found = $implementations->firstWhere('kode_topik', $default['kode']);
            if ($found) {
                $mergedImplementations[] = $found;
            } else {
                // Create a dummy object for empty display
                $dummy = new \stdClass();
                $dummy->poli_unit = $default['poli_unit'];
                $dummy->nama_topik = $default['topik'];
                $dummy->created_at = null;
                $dummy->verifikasi = null;
                $dummy->ttd_pasien = null;
                $dummy->ttd_edukator = null;
                $dummy->tgl_reedukasi = null;
                $dummy->tgl_akhir_edukasi = null;
                $mergedImplementations[] = $dummy;
            }
        }

        // 2. Append custom topics (kode_topik usually custom or is_custom = 1, but let's just append those not in default)
        $defaultKodes = array_column($topikEdukasi, 'kode');
        $customs = $implementations->whereNotIn('kode_topik', $defaultKodes);
        foreach ($customs as $c) {
            $mergedImplementations[] = $c;
        }

        $ip = $request->header('X-Real-IP') ?? $request->header('X-Forwarded-For') ?? $request->ip();
        if (strpos($ip, ',') !== false) {
            $ip = explode(',', $ip)[0]; // get the first IP in the list
        }

        $deviceInfo = [
            'ip' => trim($ip),
            'lat' => $request->query('lat', '-'),
            'lng' => $request->query('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOption('isPhpEnabled', true)->loadView('edukasi_pasien.pdf', [
            'assessment' => $assessment,
            'implementations' => $mergedImplementations,
            'deviceInfo' => $deviceInfo
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('Edukasi_Pasien_' . str_replace('/', '_', $assessment->no_rawat) . '.pdf');
    }

    /**
     * Tampilkan Tanda Tangan dari storage private
     */
    public function showSignature($filename)
    {
        $path = storage_path('app/private/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }
}
