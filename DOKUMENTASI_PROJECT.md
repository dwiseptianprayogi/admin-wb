# Dokumentasi Project Admin WB

Terakhir diperbarui: 2026-06-02

## Ringkasan

Admin WB adalah aplikasi administrasi weighbridge untuk proses timbang kendaraan Raw Material (RM) dan Finish Good (FG). Aplikasi ini mengelola master data kendaraan dan transporter, input timbang masuk/keluar, approval selisih berat, cetak slip timbang, report pembayaran transporter, import/export data, audit log, dan integrasi device timbangan.

Project ini berbasis Laravel dengan UI Blade dari template Sneat Bootstrap. Data utama aplikasi dimodelkan lewat Eloquent, tetapi banyak model tidak memakai nama tabel Laravel standar. Model memakai tabel Epicor/ICE UD seperti `Ice.UD100`, `Ice.UD101A`, dan seterusnya, lalu field aplikasi dimap ke kolom ICE seperti `Key1`, `Character01`, `Number01`, `Date01`.

## Stack

- Backend: Laravel 10, PHP 8.1+
- Frontend: Blade, Bootstrap 5, Sneat Admin Template, jQuery
- Table/filter UI: DataTables dan Select2
- PDF: `barryvdh/laravel-dompdf`
- Auth dan authorization: Laravel Auth + Spatie Permission, dengan mapping custom ke tabel ICE
- Audit: Yajra Auditable + trait custom `App\Traits\Auditable`
- Asset build: Laravel Mix, npm
- Database: SQL Server/Epicor ICE untuk production, walaupun `.env.example` masih bawaan Laravel/MySQL

## Struktur Folder Penting

- `routes/web.php`: route web utama, route transaksi, master data, auth, report, debug PDF/CSV.
- `routes/api.php`: API device timbangan.
- `app/Http/Controllers/apps`: controller modul aplikasi.
- `app/Http/Requests`: validasi form request.
- `app/Models`: model Eloquent dengan mapping atribut ke tabel ICE.
- `app/Traits/DynamicAttributeMapper.php`: mapper field aplikasi ke kolom database.
- `app/Traits/Auditable.php`: audit custom untuk create/update/delete.
- `app/Utils/Generator.php`: generator nomor slip `FGyymmdd0001` dan `RMyymmdd0001`.
- `resources/views/content`: halaman Blade per modul.
- `resources/views/content/weight-bridge/print`: template PDF slip dan report.
- `resources/menu/verticalMenu.json`: konfigurasi menu sidebar.
- `database/seeders`: seed master awal, role, permission, user, device.
- `config/permission.php`: mapping tabel Spatie Permission ke tabel ICE.

## Setup Lokal

Requirement umum:

- PHP 8.1 atau lebih baru
- Composer
- Node.js dan npm
- Extension database sesuai environment, misalnya `pdo_sqlsrv` dan `sqlsrv` jika memakai SQL Server
- Akses database Epicor/ICE atau database development yang punya struktur setara

Langkah instalasi:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Konfigurasi `.env` minimal:

```env
APP_NAME="Admin WB"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlsrv
DB_HOST=your-db-host
DB_PORT=1433
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

DEVICE_SECRET=your-device-secret
```

Jalankan asset dan server:

```bash
npm run dev
php artisan serve
```

Untuk production asset:

```bash
npm run prod
```

Catatan migrasi:

- Project punya file migration Laravel, tetapi model production banyak mengarah ke tabel `Ice.UDxx`.
- Jangan menjalankan `php artisan migrate` ke database Epicor/ICE production tanpa approval DBA.
- Seeder tersedia untuk data awal, tetapi review dulu sebelum production karena ada default user dan default device.

## Modul Utama

### Dashboard

Route: `GET /`

Controller: `App\Http\Controllers\dashboard\Analytics@index`

Menampilkan ringkasan data weighbridge, approval, dan analytics.

### Device Timbangan

API device:

```http
POST /api/weight/store
Header: secret: <DEVICE_SECRET>
Body: weight=<angka>
```

Controller: `DeviceController@create`

Alur:

- Device mengirim berat terbaru.
- Sistem mencari device berdasarkan header `secret`.
- `previous_weight` diisi dari `current_weight` lama.
- `current_weight` diupdate dari request.
- Status menjadi `stable` jika selisih berat <= `tolerance`.
- Jika berat `0`, status dipaksa `unstable`.

Endpoint detail device:

```http
GET /device
```

Controller: `DeviceController@detail`

Dipakai UI untuk membaca berat aktif dan status stabil/tidak stabil berdasarkan `DEVICE_SECRET` dari `.env`.

### Master Data

Base route: `/master-data`

Modul:

- Vehicle: `/master-data/vehicle`
- Vehicle Type: `/master-data/vehicle-type`
- Transporter: `/master-data/transporter`
- Transporter Rate: `/master-data/transporter-rate`
- Area: `/master-data/area`
- Region: `/master-data/region`

Setiap modul umumnya punya list, create, store, edit, update, delete, dan dilindungi permission seperti `view vehicle`, `create vehicle`, `edit vehicle`, `delete vehicle`.

### Transaksi Weight Bridge

Base route: `/transaction/weight-bridge`

Route penting:

- `GET /data`: list data timbang RM dan FG.
- `GET /view/{uuid}`: detail data timbang.
- `GET /receiving-material`: form timbang Raw Material.
- `GET /finish-good`: form timbang Finish Good.
- `POST /weight-in`: proses timbang masuk.
- `POST /weight-out`: proses timbang keluar.
- `GET /print/{uuid}/slip`: cetak slip timbang.
- `GET /report`: report transporter, termasuk export PDF/CSV.

Controller utama: `WeightBridgeController`

### Approval

Route:

- `GET /transaction/weight-bridge/approval`
- `POST /transaction/weight-bridge/approval/approve/{approvalUuid}`
- `POST /transaction/weight-bridge/approval/reject/{approvalUuid}`

Controller: `ApprovalController`

Approval dipakai untuk transaksi FG ketika selisih berat melebihi toleransi vehicle type. Flow approval mendukung dua level:

- Level 1 memakai permission `approve` dan `reject`.
- Level 2 memakai permission `approve 2` dan `reject 2`.

Jika dua level approval sudah approve, status weighbridge dikembalikan menjadi `FG-OUT`. Jika reject, data lama menjadi `REJECTED` dan sistem membuat ulang record baru dari data lama dengan status awal `FG-IN`.

### Report Transporter

Route:

```http
GET /transaction/weight-bridge/report
```

Controller: `WeightBridgeController@transporterReport`

Filter yang tersedia:

- Period from/to
- Transporter
- Area
- Vehicle group
- D/O number
- Product
- Kwitansi No
- WB Doc

Export:

- PDF: tambah query `export=PDF`
- CSV: tambah query `export=CSV`

Contoh:

```http
/transaction/weight-bridge/report?period_from=2026-04-01&period_to=2026-04-30&export=PDF
```

Template:

- Web view: `resources/views/content/weight-bridge/report.blade.php`
- PDF view: `resources/views/content/weight-bridge/print/report.blade.php`

Data report diambil dari raw SQL yang join ke tabel `ShipHead`, `ShipDtl`, `Ice.UD100`, `Ice.UD101A`, `Ice.UD101`, `Ice.UD102`, `Ice.UD103A`, dan `Ice.UD102A`. Hasil report digroup berdasarkan transporter, lalu di view digroup lagi per area untuk subtotal.

### Print Slip

Route:

```http
GET /transaction/weight-bridge/print/{uuid}/slip
```

Controller: `PrintController@generateSlipPDF`

Template:

```text
resources/views/content/weight-bridge/print/slip.blade.php
```

PDF slip memakai ukuran kertas custom sekitar 75mm, dengan tinggi dinamis berdasarkan jumlah detail SPB.

### Import dan Export Data

Base route: `/data`

- `GET /data/export?table=Vehicle`
- `POST /data/import`
- `GET /data/download-template?table=Vehicle`

Controller: `ExportImportController`

Cara kerja:

- Export menerima nama model via query `table`.
- Import membaca CSV dan mencocokkan kolom dengan `fillable` model.
- Kolom dengan suffix `_uuid` akan diresolve dari kode related model (`ShortChar01`).
- `Vehicle` punya dukungan tambahan `transporter_multiple_code`.
- Date `start_date` dan `end_date` pada import diharapkan format `d-m-Y`.

## Flow Timbang

### Weight In

Endpoint:

```http
POST /transaction/weight-bridge/weight-in
```

Validasi: `WeightInRequest`

Field:

- `weight_in`: required, max 10 karakter
- `vehicle_no`: required
- `remark`: nullable, max 50 karakter
- `weighing_type`: required, `rm` atau `fg`

Alur utama:

1. Sistem menentukan tipe timbang `rm` atau `fg`.
2. Untuk FG, kendaraan harus terdaftar dan statusnya `active`.
3. Sistem mengecek apakah kendaraan masih punya proses timbang lain atau approval yang belum selesai.
4. Jika input otomatis, device harus berstatus `stable`.
5. Sistem generate slip nomor lewat `Generator::generateSlipNo()`.
6. Record dibuat di `WeightBridge`.
7. Device direset ke `current_weight=0`, `previous_weight=0`, status `unstable`.
8. User dikembalikan ke form RM atau FG dengan pesan sukses.

### Weight Out

Endpoint:

```http
POST /transaction/weight-bridge/weight-out
```

Validasi: `WeightOutRequest`

Field:

- `weight_out`: required, max 10 karakter
- `vehicle_no`: required
- `remark`: nullable, max 50 karakter
- `po_do`: nullable, max 255 karakter
- `weighing_type`: required, `rm` atau `fg`
- `weight_standart_epicor`: required

Alur utama:

1. Sistem mencari data weight-in aktif dengan status `RM-IN` atau `FG-IN`.
2. Jika input otomatis, device harus `stable`.
3. Untuk RM, `weight_out` harus lebih kecil dari `weight_in`, lalu netto = `weight_in - weight_out`.
4. Untuk FG, netto = `weight_out - weight_in`.
5. Status transaksi berubah menjadi `RM-OUT` atau `FG-OUT`.
6. Untuk FG, sistem mengambil total berat dari `ShipHead` dan `ShipDtl`.
7. Jika selisih netto dengan total berat Epicor melebihi tolerance vehicle type, sistem membuat approval dan status menjadi `WAITING FOR APPROVAL`.
8. Jika tidak perlu approval, user diarahkan ke PDF slip timbang.

## Data Model dan Mapping Tabel

Trait `DynamicAttributeMapper` membuat developer bisa memakai nama field yang lebih mudah dibaca di kode, walaupun kolom database aslinya adalah kolom ICE.

Contoh dari `WeightBridge`:

- `uuid` -> `Key1`
- `slip_no` -> `Character01`
- `vehicle_no` -> `Character08`
- `weight_type` -> `ShortChar01`
- `status` -> `ShortChar02`
- `weight_in` -> `Number01`
- `weight_out` -> `Number02`
- `weight_netto` -> `Number03`
- `weight_standart` -> `Number04`
- `difference` -> `Number05`

Mapping model utama:

| Model | Tabel aktual | Keterangan |
| --- | --- | --- |
| `User` | `Ice.UD01` | User login |
| `Permission` | `Ice.UD02` | Permission Spatie |
| `Role` | `Ice.UD03` | Role Spatie |
| `AuditLog` | `Ice.UD04` | Audit log aplikasi |
| `Device` | `Ice.UD05` | Device timbangan |
| `RoleHasPermission` | `Ice.UD06` | Pivot role-permission |
| `ModelHasRole` | `Ice.UD08` | Pivot user-role |
| `VehicleTransporter` | `Ice.UD09` | Multiple transporter untuk vehicle |
| `WeightBridge` | `Ice.UD100` | Transaksi timbang |
| `WeightBridgeApproval` | `Ice.UD100A` | Approval transaksi timbang |
| `VehicleType` | `Ice.UD101` | Jenis kendaraan |
| `Vehicle` | `Ice.UD101A` | Kendaraan |
| `Transporter` | `Ice.UD102` | Transporter |
| `TransporterRate` | `Ice.UD102A` | Tarif transporter |
| `Region` | `Ice.UD103` | Region |
| `Area` | `Ice.UD103A` | Area |

Tabel eksternal yang dipakai report dan slip:

- `ShipHead`
- `ShipDtl`
- `Part`

## Permission

Permission dibuat dari `database/seeders/PermissionSeeder.php` dan dipasang ke role `SUPER_ADMIN`.

Kategori permission:

- User: `create user`, `edit user`, `delete user`, `view user`, `export user`, `import user`
- Vehicle: `create vehicle`, `edit vehicle`, `delete vehicle`, `view vehicle`, `export vehicle`, `import vehicle`
- Vehicle Type: `create vehicle_type`, `edit vehicle_type`, `delete vehicle_type`, `view vehicle_type`, `export vehicle_type`, `import vehicle_type`
- Area/Region: `create area`, `edit area`, `delete area`, `view area`, `create region`, `edit region`, `delete region`, `view region`
- Transporter: `create transporter`, `edit transporter`, `delete transporter`, `view transporter`, `export transporter`, `import transporter`
- Transporter Rate: `create transporter_rate`, `edit transporter_rate`, `delete transporter_rate`, `view transporter_rate`, `export transporter_rate`, `import transporter_rate`
- Weight Bridge: `view receiving_material`, `view finish_good`, `weight_in`, `manual_input`, `weight_out`, `print_rw`, `print_fg`, `view data_wb`
- Approval: `approve`, `reject`, `approve 2`, `reject 2`, `view approval`
- Lainnya: `view analytics`, `view group`, `create group`, `edit group`, `view log`, `view report`

Catatan: `UserSeeder` membuat user awal untuk `SUPER_ADMIN`. Review dan ubah credential seed sebelum dipakai di production.

## File View Penting

- `resources/views/content/weight-bridge/receiving-material.blade.php`: form timbang RM.
- `resources/views/content/weight-bridge/finish-good.blade.php`: form timbang FG.
- `resources/views/content/weight-bridge/list.blade.php`: list data timbang.
- `resources/views/content/weight-bridge/view-receiving-material.blade.php`: detail transaksi RM.
- `resources/views/content/weight-bridge/view-finish-good.blade.php`: detail transaksi FG.
- `resources/views/content/weight-bridge/report.blade.php`: report transporter web.
- `resources/views/content/weight-bridge/print/slip.blade.php`: PDF slip timbang.
- `resources/views/content/weight-bridge/print/report.blade.php`: PDF report transporter.
- `resources/views/content/approval/list.blade.php`: list approval.
- `resources/views/content/log/list.blade.php`: audit log.

## Debug Route

Di `routes/web.php` ada route debug:

- `/debug/report-dummy`
- `/debug/print-sample`
- `/debug/print-rm-sample`

Route ini berguna untuk testing PDF/CSV tanpa akses data real. Untuk production, route debug sebaiknya dibatasi, dipindahkan ke environment local, atau dihapus.

## Catatan Teknis Penting

- `.env.example` masih generic Laravel dan belum mendokumentasikan `DEVICE_SECRET`.
- Banyak model memakai mapping ICE. Saat mengubah nama field, cek juga `setAttributeMapping()` di model.
- Report transporter memakai raw SQL yang disusun dari request filter. Untuk hardening security, pertimbangkan parameter binding.
- `WeightBridgeController` dan `PrintController` melakukan query langsung ke tabel `ShipHead`, `ShipDtl`, dan `Part`, jadi koneksi database harus punya akses ke tabel tersebut.
- `barryvdh/laravel-snappy` ada di dependency, tetapi flow PDF yang terlihat saat ini memakai DomPDF.
- Beberapa route import/export menerima nama model dari query `table`; pastikan permission route cukup ketat sebelum exposure ke user umum.
- Seeder default harus direview sebelum production, terutama user awal dan `DEVICE_SECRET`.

## Checklist Deploy

1. Pastikan PHP extension database sudah sesuai target database.
2. Set `.env` production: `APP_ENV=production`, `APP_DEBUG=false`, DB credential, dan `DEVICE_SECRET`.
3. Jalankan `composer install --no-dev --optimize-autoloader`.
4. Jalankan `npm ci` lalu `npm run prod` jika asset perlu dibuild ulang.
5. Jalankan cache Laravel sesuai kebutuhan:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. Pastikan folder `storage` dan `bootstrap/cache` writable.
7. Review dan nonaktifkan route debug di production.
8. Tes flow kritikal: login, `/device`, weight-in, weight-out, approval, print slip, report PDF/CSV.

