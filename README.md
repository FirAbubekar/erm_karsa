# Karsa ERM - Electronic Medical Record (Rekam Medis Elektronik)

Karsa ERM adalah sistem Rekam Medis Elektronik (RME) berbasis web yang dirancang khusus untuk mempermudah administrasi klinis, pencatatan rekam medis, dan persetujuan tindakan medis. Aplikasi ini diintegrasikan secara langsung dengan database **SIMKES Khanza** (`sik`) untuk sinkronisasi data pasien, pegawai, dan hasil penunjang medis lainnya.

---

## 🚀 Fitur Utama

### 1. RM 01 - General Consent (Persetujuan Umum)
* **Input General Consent:** Form pengisian persetujuan umum bagi pasien baru maupun rawat inap.
* **Riwayat & Pencarian:** Daftar pencarian dokumen persetujuan umum yang telah diinput sebelumnya.
* **Tanda Tangan Digital:** Integrasi penandatanganan elektronik untuk pasien/penanggung jawab.
* **Download PDF:** Cetak dokumen General Consent berformat PDF standar rekam medis.
* **WhatsApp Gateway:** Pengiriman tautan/dokumen General Consent langsung ke nomor WhatsApp pasien.

### 2. RM 02 - Surat Persetujuan Rawat Inap (Ranap)
* **Input Surat Persetujuan:** Pengisian surat persetujuan rawat inap lengkap dengan data penanggung jawab dan kelas kamar.
* **Riwayat Surat:** Pemantauan berkas persetujuan rawat inap yang telah diterbitkan.
* **Unduh PDF:** Cetak surat persetujuan rawat inap resmi rumah sakit dalam format PDF.
* **Notifikasi WhatsApp:** Kirim notifikasi konfirmasi rawat inap menggunakan template pesan WhatsApp.

### 3. Modul Laboratorium
* **Hasil Lab:** Pencarian dan penampilan hasil laboratorium pasien secara *real-time*.
* **Detail Hasil:** Detail analisis parameter lab beserta nilai rujukan normal.
* **Ekspor PDF:** Mengunduh hasil laboratorium lengkap dalam bentuk PDF.
* **Kirim WA:** Mengirimkan berkas hasil lab dalam format digital via WhatsApp.

### 4. Role-Based Access Control (RBAC) - Manajemen Hak Akses
Sistem otorisasi dinamis yang membatasi hak akses menu dan tombol berdasarkan jabatan pegawai:
* **`web_permissions`:** Menyimpan daftar hak akses spesifik (contoh: `gc.create`, `gc.view`, `ranap.create`, `lab.view`).
* **`web_role_permissions`:** Menghubungkan Role/Jabatan dengan Hak Akses (contoh: Role `J028` memiliki hak akses rawat inap dan general consent).
* **`web_user_roles`:** Memetakan NIP/NIK pegawai ke Role/Jabatan tertentu.
* **Helper `hasPermission()`:** Pengecekan izin langsung di Blade View secara instan.

### 5. Pelacakan Aktivitas (Audit Logs)
* Perekaman otomatis setiap aksi tambah (create), ubah (update), dan hapus (delete) data penting ke tabel `audit_logs` menggunakan Trait `HasAuditLogs` pada model Eloquent untuk keperluan audit dan keamanan data.

---

## 🛠️ Spesifikasi Teknologi

* **Framework Utama:** Laravel 11 (PHP >= 8.2)
* **Database:** MySQL / MariaDB (Koneksi ke database SIMKES Khanza `sik`)
* **PDF Generator:** Barryvdh Laravel DomPDF
* **WhatsApp API:** Integrasi Webhook / HTTP API Gateway
* **Frontend:** Vanilla CSS (Desain modern, responsif, dan ramah mobile)

---

## ⚙️ Panduan Instalasi

### 1. Kloning Repositori
```bash
git clone https://github.com/FirAbubekar/erm_karsa.git
cd erm_karsa
```

### 2. Instal Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasinya:
```bash
cp .env.example .env
```
Sesuaikan parameter koneksi database SIMKES Khanza dan API WhatsApp:
```ini
DB_CONNECTION=mysql
DB_HOST=192.168.200.201
DB_PORT=3306
DB_DATABASE=sik
DB_USERNAME=simrs
DB_PASSWORD='your_password'

# WhatsApp Gateway
WA_URL=http://192.168.200.182:3004
WA_DEVICE_ID=your_device_id

# Path TTD Pegawai
STAFF_SIGNATURE_PATH=http://192.168.30.24/webapps/penggajian/temp
```

### 4. Generate App Key & Jalankan Migrasi
```bash
php artisan key:generate
php artisan migrate
```
*(Migrasi ini akan membuat tabel tambahan seperti `audit_logs` pada database Anda).*

### 5. Refresh Autoload File Helper
Karena project menggunakan custom helper untuk pengecekan hak akses, jalankan perintah berikut:
```bash
composer dump-autoload
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi dapat diakses di `http://localhost:8000` atau sesuai port yang diberikan.

---

## 📂 Struktur Database Manajemen Hak Akses (RBAC)

Berikut adalah struktur tabel yang digunakan untuk otorisasi menu pada aplikasi ini:

### `web_permissions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | int(11) (PK) | ID unik permission |
| `name` | varchar(100) | Nama hak akses (contoh: Lihat General Consent) |
| `slug` | varchar(100) | Kode unik checking (contoh: `gc.view`) |
| `group` | varchar(100) | Kategori (contoh: General Consent) |

### `web_role_permissions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | int(11) (PK) | ID unik mapping |
| `role_id` | varchar(100) | ID/Kode Jabatan (contoh: `J028`) |
| `permission_id`| int(11) | ID Permission (relasi ke `web_permissions.id`) |

### `web_user_roles`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | int(11) (PK) | ID unik mapping user |
| `nip` | varchar(100) | NIP Pegawai (relasi ke `pegawai.nik`) |
| `role_id` | varchar(100) | ID/Kode Jabatan (relasi ke `web_role_permissions.role_id`) |

---

## 💻 Penggunaan dalam Kode (Developer Guide)

### Pengecekan Izin di Blade View (Tampilan)
Gunakan helper `hasPermission` untuk menyembunyikan atau menampilkan elemen UI secara dinamis:
```html
@if(hasPermission('ranap.create'))
    <button class="btn btn-primary">Buat Surat Ranap</button>
@endif
```

### Pengecekan Izin di Controller / Backend
Gunakan `PermissionService` saat login untuk memuat hak akses ke session:
```php
use App\Services\PermissionService;

$permissions = PermissionService::getPermissions($username);
Session::put('permissions', $permissions);
```

### Merekam Aktivitas (Audit Logs) pada Model
Cukup gunakan Trait `HasAuditLogs` pada model Eloquent Anda untuk melacak setiap perubahan data:
```php
namespace App\Models;

use App\Traits\HasAuditLogs;
use Illuminate\Database\Eloquent\Model;

class SuratPersetujuanRawatInap extends Model
{
    use HasAuditLogs;
    
    // Model code here...
}
```
