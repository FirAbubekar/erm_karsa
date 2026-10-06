<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProspectiveReviuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'no_rawat' => 'required|string|max:50',
            'tanggal_reviu' => 'required|date',
            'tipe_antibiotik' => 'nullable|string|max:255',
            'antibiotik_direview' => 'nullable|string|max:100',
            'hari_ke' => 'nullable|integer',
            'klinis_td' => 'nullable|string|max:20',
            'klinis_suhu' => 'nullable|numeric|between:0,99.99',
            'klinis_rr' => 'nullable|integer',
            'klinis_spo2' => 'nullable|integer',
            'klinis_gcs' => 'nullable|string|max:20',
            'is_demam' => 'nullable|boolean',
            'lab_leukosit' => 'nullable|numeric|between:0,99999999.99',
            'lab_neutrofil_persen' => 'nullable|numeric|between:0,100',
            'lab_kreatinin' => 'nullable|numeric|between:0,99999999.99',
            'lab_ureum' => 'nullable|numeric|between:0,99999999.99',
            'kultur_status' => 'nullable|string|max:50',
            'kultur_hasil_positif' => 'nullable|string',
            'kultur_rekomendasi_antibiotik' => 'nullable|string',
            'is_indikasi_tepat' => 'nullable|boolean',
            'is_jenis_tepat' => 'nullable|boolean',
            'is_dosis_tepat' => 'nullable|boolean',
            'is_durasi_sesuai' => 'nullable|boolean',
            'rekomendasi_pga' => 'nullable|array',
            'rekomendasi_pga_lainnya' => 'nullable|string|max:255',
            'respon_dpjp' => 'nullable|string|max:50',
            'respon_catatan' => 'nullable|string',
            'ttd_apoteker_klinis' => 'nullable|string|max:255',
            'ttd_perawat' => 'nullable|string|max:255',
            'ttd_dpjp' => 'nullable|string|max:255',
            'ttd_kpra' => 'nullable|string|max:255',
            'diagnosis' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric'  => 'Kolom :attribute harus berupa angka.',
            'integer'  => 'Kolom :attribute harus berupa angka (tanpa desimal).',
            'string'   => 'Kolom :attribute harus berupa teks.',
            'max'      => 'Kolom :attribute maksimal :max karakter.',
            'between'  => 'Nilai :attribute harus berada di antara :min dan :max.',
            'date'     => 'Kolom :attribute harus berupa format tanggal yang valid.',
            'boolean'  => 'Format kolom :attribute tidak valid.',
            'array'    => 'Format kolom :attribute tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'no_rawat' => 'No. Rawat',
            'tanggal_reviu' => 'Tanggal Reviu',
            'tipe_antibiotik' => 'Tipe Antibiotik',
            'antibiotik_direview' => 'Antibiotik (Review)',
            'hari_ke' => 'Hari Ke-',
            'klinis_td' => 'Tekanan Darah',
            'klinis_suhu' => 'Suhu',
            'klinis_rr' => 'Respiration Rate (RR)',
            'klinis_spo2' => 'SpO2',
            'klinis_gcs' => 'GCS',
            'lab_leukosit' => 'Leukosit',
            'lab_neutrofil_persen' => 'Neutrofil (%)',
            'lab_kreatinin' => 'Kreatinin',
            'lab_ureum' => 'Ureum',
        ];
    }

    /**
     * Override JSON response format for failed validation to match the frontend expectations.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
