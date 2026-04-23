<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik', 'nama', 'jk', 'jbtn', 'jnj_jabatan',
        'departemen', 'bidang', 'stts_aktif'
    ];

    /**
     * Get the absolute path to the signature file if it exists.
     * Checks for .png then .jpg extensions.
     */
    public function getSignaturePathAttribute()
    {
        $nik = trim($this->nik);
        $baseUrl = env('STAFF_SIGNATURE_PATH', 'http://192.168.30.24/webapps/penggajian/temp');
        $extensions = ['png', 'jpg', 'jpeg'];

        foreach ($extensions as $ext) {
            $url = $baseUrl . '/' . $nik . '.' . $ext;
            
            // Check if URL exists using HTTP HEAD request
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(2)->head($url);
                if ($response->successful()) {
                    return $url;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error checking remote signature: " . $e->getMessage());
            }
        }

        \Illuminate\Support\Facades\Log::debug("Remote signature not found for NIK: '{$nik}' in URL: '{$baseUrl}'");

        return null;
    }

    /**
     * Get the signature as a base64 encoded string for embedding in PDFs.
     */
    public function getSignatureBase64Attribute()
    {
        $url = $this->signature_path;
        if ($url) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
                if ($response->successful()) {
                    $type = pathinfo($url, PATHINFO_EXTENSION);
                    $data = $response->body();
                    return 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error fetching remote signature file: " . $e->getMessage());
            }
        }
        return null;
    }
}
