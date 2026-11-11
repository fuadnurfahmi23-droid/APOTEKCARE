<div align="center">

# 💊 ApotekCare

**Sistem Informasi Manajemen Inventaris dan Kasir Apotek Berbasis Web**
dengan Monitoring Kedaluwarsa, Pengelolaan Batch, dan Rekomendasi Prioritas Restock Obat

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

</div>

---

## 📖 Tentang Proyek

**ApotekCare** adalah aplikasi web yang membantu mengoptimalkan operasional apotek, terutama dalam pengelolaan persediaan obat, pemantauan tanggal kedaluwarsa, dan pencatatan transaksi penjualan.

Sistem ini dikembangkan untuk mengurangi masalah pengelolaan stok manual, seperti:

- Sulit memantau ketersediaan obat
- Risiko obat kedaluwarsa
- Pencatatan batch yang tidak teratur
- Keterlambatan pengadaan stok

ApotekCare menggabungkan **manajemen inventaris** dan **kasir** dalam satu platform. Transaksi penjualan memperbarui stok secara otomatis, pengeluaran batch mengikuti prinsip **FEFO**, dan kondisi persediaan menjadi dasar **rekomendasi restock**.

## ✨ Keunggulan Utama

| Fitur | Deskripsi |
|-------|-----------|
| 📦 **Batch-Based Inventory** | Stok dicatat per batch lengkap dengan nomor batch, jumlah, tanggal masuk, dan tanggal kedaluwarsa |
| ⏳ **Automatic FEFO** | Batch dengan kedaluwarsa terdekat otomatis dikeluarkan lebih dulu (*First Expired, First Out*) |
| 🧠 **Smart Restock Recommendation** | Rekomendasi pengadaan berdasarkan kondisi persediaan dan batas minimum |

## 🚀 Fitur

### 📊 Dashboard
- Ringkasan total jenis obat dan total stok tersedia
- Jumlah obat dengan stok menipis/habis
- Jumlah batch yang mendekati atau telah kedaluwarsa
- Jumlah dan nilai transaksi penjualan
- Grafik penjualan harian dan bulanan
- Notifikasi penting terkait stok dan kedaluwarsa

### 💊 Manajemen Data Obat
- CRUD data obat (kode, nama, kategori, jenis, satuan)
- Pencatatan harga beli dan harga jual
- Relasi obat dengan pemasok
- Pencarian, filter, dan pengurutan data

### 📦 Manajemen Stok dan Batch
- Pencatatan stok masuk dan pembuatan batch
- Stok per batch dan total stok per obat
- Riwayat pergerakan stok masuk dan keluar
- Pengurangan stok otomatis dengan prinsip FEFO

### ⏰ Monitoring Kedaluwarsa
- Pemantauan tanggal kedaluwarsa per batch
- Status: **Aman**, **Segera Kedaluwarsa**, **Kedaluwarsa**
- Notifikasi batch yang mendekati kedaluwarsa
- Daftar batch yang dapat diurutkan berdasarkan tanggal kedaluwarsa

### 🛒 Kasir dan Transaksi Penjualan
- Pencarian obat berdasarkan nama atau kode
- Keranjang transaksi (tambah, ubah jumlah, hapus item)
- Perhitungan subtotal, total, nominal bayar, dan kembalian otomatis
- Cetak struk dan riwayat penjualan

### 🔔 Rekomendasi Prioritas Restock
- Identifikasi obat di bawah/mendekati batas minimum
- Prioritas **tinggi**, **sedang**, **rendah**
- Saran jumlah pemesanan berdasarkan aturan yang ditetapkan
- Dapat ditinjau Admin/Inventori sebelum pengadaan

### 🏭 Manajemen Pemasok
- CRUD data pemasok (nama, alamat, telepon)
- Relasi pemasok dengan obat
- Riwayat penerimaan obat

### 📑 Laporan dan Riwayat
- Laporan penjualan harian dan bulanan
- Laporan persediaan, stok masuk, dan stok keluar
- Laporan batch mendekati/melewati kedaluwarsa
- Ringkasan pendapatan dan keuntungan
- Filter rentang tanggal, cetak, dan ekspor

### 👥 Manajemen Pengguna
- Login dan logout
- Role **Admin/Inventori** dan **Kasir**
- Pembatasan akses halaman dan aksi menggunakan Middleware

## 🔐 Hak Akses

| Fitur | Admin / Inventori | Kasir |
|-------|:-----------------:|:-----:|
| Dashboard | Ringkasan inventaris dan laporan | Ringkasan penjualan sesuai izin |
| Data obat | Tambah, ubah, hapus, lihat | Lihat informasi obat |
| Stok dan batch | ✅ Kelola penuh | ❌ |
| Monitoring kedaluwarsa | ✅ | ❌ |
| Kasir / transaksi | Lihat sesuai kewenangan | ✅ Buat dan proses transaksi |
| Restock dan pemasok | ✅ | ❌ |
| Laporan | Inventaris dan penjualan | Terbatas jika diperlukan |
| Manajemen pengguna | ✅ Kelola akun dan hak akses | Kelola profil sendiri |

## 🔄 Alur Kerja

```
Data Obat → Stok Masuk & Batch → Monitoring Stok/Kedaluwarsa
    → Transaksi Kasir → Pengurangan Stok (FEFO)
    → Rekomendasi Restock → Laporan
```

> **Catatan:** transaksi dan pengurangan stok dijalankan dalam satu *database transaction* agar data tidak setengah berhasil. Stok divalidasi ulang sebelum transaksi disimpan.

## 🧮 Algoritma dan Logika

| Konsep | Penerapan |
|--------|-----------|
| **FEFO** | Mengurutkan batch berdasarkan kedaluwarsa terdekat, lalu mengeluarkan stok dari batch tersebut lebih dulu |
| **Searching** | Pencarian obat berdasarkan kode, nama, kategori |
| **Sorting** | Pengurutan berdasarkan nama, stok, tanggal masuk, atau tanggal kedaluwarsa |
| **Greedy Algorithm** | Penentuan prioritas restock berdasarkan skor/kriteria kebutuhan paling mendesak |
| **Conditional Logic** | Status stok (aman/menipis/habis) dan status kedaluwarsa |
| **Aggregation** | Total stok, transaksi, pendapatan, dan ringkasan laporan |

## 🛠️ Teknologi

| Teknologi | Fungsi |
|-----------|--------|
| **Laravel** | Framework backend |
| **PHP** | Bahasa pemrograman sisi server |
| **MySQL** | Basis data |
| **Bootstrap 5** | Antarmuka responsif |
| **Routing** | Pengaturan jalur akses |
| **Middleware** | Autentikasi dan otorisasi berdasarkan role |
| **Migration** | Pengelolaan struktur tabel via kode |
| **Seeder** | Data awal (role, akun uji, kategori, dsb.) |
| **Eloquent ORM** | Interaksi data dan relasi antarmodel |

## 🗂️ Struktur Model

| Model | Tanggung Jawab |
|-------|----------------|
| `User` | Akun pengguna dan role |
| `Obat` | Data induk obat, harga, batas minimum |
| `BatchObat` | Nomor batch, tanggal masuk/kedaluwarsa, jumlah awal, sisa stok |
| `Pemasok` | Data dan kontak pemasok |
| `Transaksi` | Header transaksi (nomor, kasir, total, bayar, kembalian) |
| `DetailTransaksi` | Item yang terjual |
| `DetailBatchKeluar` | Batch yang digunakan untuk memenuhi tiap item penjualan |
| `StokMasuk` | Penerimaan obat dari pemasok |
| `Restock` | Rekomendasi restock dan status tindak lanjut |

**Relasi penting:**
- Satu `Obat` memiliki banyak `BatchObat`
- Satu `Transaksi` memiliki banyak `DetailTransaksi`
- Satu `DetailTransaksi` dapat mengambil dari satu atau lebih batch
- Satu `User` (kasir) dapat membuat banyak `Transaksi`

## 📏 Aturan Bisnis

- Tanggal kedaluwarsa disimpan di **tingkat batch**, bukan pada data induk obat
- Total stok obat = akumulasi sisa stok batch yang masih valid
- Batch kedaluwarsa **tidak boleh** dijual
- Penjualan memprioritaskan batch dengan kedaluwarsa terdekat yang masih valid (FEFO)
- Jika satu batch tidak cukup, sisa kebutuhan diambil dari batch berikutnya
- Stok tidak boleh negatif; ketersediaan divalidasi sebelum transaksi disimpan
- Pembatalan/pengembalian harus mengembalikan stok ke batch asal (jika layak)
- Rekomendasi restock hanya saran; keputusan pemesanan tetap di petugas berwenang
- Ambang kedaluwarsa (misalnya 30/60/90 hari) dan batas minimum stok dapat dikonfigurasi

## 🧭 Menu Navigasi

`Dashboard` · `Data Obat` · `Stok dan Batch Obat` · `Monitoring Kedaluwarsa` · `Kasir / Penjualan` · `Rekomendasi Restock` · `Data Pemasok` · `Laporan Penjualan` · `Riwayat Transaksi` · `Manajemen Pengguna` · `Pengaturan`

## ⚙️ Instalasi

### Prasyarat

- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM (opsional, untuk aset frontend)

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/username/apotekcare.git
cd apotekcare

# 2. Install dependensi
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate
```

Atur koneksi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotekcare
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 5. Jalankan migration dan seeder
php artisan migrate --seed

# 6. Jalankan aplikasi
php artisan serve
```

Buka `http://localhost:8000` di browser.

### 🔑 Akun Demo (dari Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin / Inventori | `admin@apotekcare.test` | `password` |
| Kasir | `kasir@apotekcare.test` | `password` |

> ⚠️ Sesuaikan dengan data pada seeder Anda, dan ubah password default sebelum digunakan di lingkungan produksi.

## 🗓️ Roadmap Pengembangan

- [ ] 1. Login, role, dan Middleware
- [ ] 2. CRUD data obat dan kategori
- [ ] 3. Stok masuk dan pencatatan batch
- [ ] 4. Monitoring kedaluwarsa dan FEFO
- [ ] 5. Kasir dan transaksi penjualan
- [ ] 6. Pengurangan stok otomatis
- [ ] 7. Rekomendasi restock
- [ ] 8. Dashboard dan laporan
- [ ] 9. Fitur tambahan: ekspor laporan, cetak struk, grafik lanjutan, manajemen profil

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur (`git checkout -b fitur/nama-fitur`)
3. Commit perubahan (`git commit -m "Tambah fitur ..."`)
4. Push ke branch (`git push origin fitur/nama-fitur`)
5. Buka Pull Request

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran/perkuliahan. Tambahkan lisensi sesuai kebutuhan (misalnya [MIT](https://opensource.org/licenses/MIT)).

## 👨‍💻 Tim Pengembang

- Nama Anggota 1 — NIM
- Nama Anggota 2 — NIM

---

<div align="center">

Dibuat dengan ❤️ menggunakan Laravel

</div>
