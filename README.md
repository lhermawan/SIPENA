# \# SIM-SPJ

# 

# \### Sistem Informasi Surat Pertanggungjawaban

# 

# SIM-SPJ adalah aplikasi berbasis \*\*Laravel\*\* dan \*\*Filament\*\* yang dirancang untuk membantu proses penyusunan, pencatatan, pengelolaan, serta pengarsipan Surat Pertanggungjawaban (SPJ) secara digital.

# 

# Aplikasi ini bertujuan mengurangi pekerjaan berulang, meminimalkan kesalahan perhitungan, mempercepat pembuatan dokumen, serta menyediakan jejak audit (Audit Log) terhadap seluruh aktivitas pengguna.

# 

# \---

# 

# \# Tujuan

# 

# \* Digitalisasi proses administrasi SPJ.

# \* Mengurangi penggunaan Microsoft Excel dan Word secara terpisah.

# \* Menghindari kesalahan perhitungan nominal.

# \* Mempermudah pencarian dokumen.

# \* Menyediakan arsip digital yang terstruktur.

# \* Menyediakan audit trail setiap perubahan data.

# \* Mendukung transparansi dan akuntabilitas pengelolaan keuangan.

# 

# \---

# 

# \# Teknologi

# 

# \* PHP 8.3+

# \* Laravel 12

# \* Filament 4

# \* MySQL / MariaDB

# \* Spatie Permission

# \* Spatie Activity Log

# \* Laravel Media Library

# \* DomPDF

# \* Laravel Excel

# 

# \---

# 

# \# Fitur Utama

# 

# \## Dashboard

# 

# Dashboard memberikan informasi secara real-time mengenai kondisi administrasi SPJ.

# 

# Fitur:

# 

# \* Total SPJ

# \* SPJ Draft

# \* SPJ Diproses

# \* SPJ Disetujui

# \* Total Belanja

# \* Grafik Realisasi Bulanan

# \* Aktivitas Terbaru

# \* Statistik Pengguna

# 

# \---

# 

# \# Struktur Modul

# 

# ```

# Dashboard

# │

# ├── Master

# │   ├── Pegawai

# │   ├── Penandatangan

# │   ├── Rekanan

# │   ├── Program

# │   ├── Kegiatan

# │   ├── Sub Kegiatan

# │   ├── Rekening Belanja

# │   └── Satuan

# │

# ├── Transaksi

# │   ├── SPJ

# │   ├── Bukti Transaksi

# │   └── Arsip

# │

# ├── Laporan

# │   ├── Rekap SPJ

# │   ├── Realisasi Anggaran

# │   ├── Rekap Rekanan

# │   └── Rekap Pajak

# │

# ├── Audit Log

# │

# └── Pengaturan

# &#x20;   ├── Penandatangan Aktif

# &#x20;   ├── Template Dokumen

# &#x20;   ├── Nomor Otomatis

# &#x20;   └── Hak Akses

# ```

# 

# \---

# 

# \# Modul Master

# 

# \## Pegawai

# 

# Digunakan sebagai referensi seluruh ASN maupun pegawai yang terlibat dalam administrasi SPJ.

# 

# Data yang disimpan:

# 

# \* Nama

# \* NIP

# \* Pangkat

# \* Golongan

# \* Jabatan

# \* Unit Kerja

# \* Status Aktif

# 

# \---

# 

# \## Penandatangan

# 

# Menyimpan seluruh pejabat yang dapat menjadi penandatangan dokumen.

# 

# Contoh:

# 

# \* PA/KPA

# \* PPK

# \* PPTK

# \* Bendahara

# \* Pengguna Anggaran

# 

# Saat membuat SPJ cukup memilih nama pejabat, maka seluruh dokumen otomatis menggunakan data tersebut.

# 

# \---

# 

# \## Rekanan

# 

# Master data penyedia barang maupun jasa.

# 

# Data:

# 

# \* Nama

# \* NPWP

# \* Alamat

# \* Nomor Rekening

# \* Nama Bank

# \* Kontak

# 

# \---

# 

# \## Program

# 

# Master Program sesuai DPA.

# 

# \---

# 

# \## Kegiatan

# 

# Master kegiatan yang berada di bawah Program.

# 

# \---

# 

# \## Sub Kegiatan

# 

# Master Sub Kegiatan sesuai struktur anggaran.

# 

# \---

# 

# \## Rekening Belanja

# 

# Berisi kode rekening sesuai standar pemerintah.

# 

# Contoh:

# 

# \* Belanja ATK

# \* Belanja Makan Minum

# \* Belanja Perjalanan Dinas

# 

# \---

# 

# \## Satuan

# 

# Digunakan pada detail transaksi.

# 

# Contoh:

# 

# \* Paket

# \* Unit

# \* Orang

# \* Hari

# \* Bulan

# \* Lembar

# 

# \---

# 

# \# Modul Transaksi

# 

# \## SPJ

# 

# Merupakan modul utama aplikasi.

# 

# Data Header:

# 

# \* Nomor SPJ

# \* Tanggal

# \* Program

# \* Kegiatan

# \* Sub Kegiatan

# \* Rekening

# \* PPTK

# \* PPK

# \* Bendahara

# \* PA/KPA

# 

# Detail Item:

# 

# \* Uraian

# \* Volume

# \* Satuan

# \* Harga Satuan

# \* Total

# 

# Fitur:

# 

# \* Perhitungan otomatis

# \* Terbilang otomatis

# \* Upload lampiran

# \* Generate PDF

# \* Generate Word

# \* Draft

# \* Finalisasi

# 

# \---

# 

# \## Bukti Transaksi

# 

# Lampiran yang dapat disimpan:

# 

# \* Invoice

# \* Nota

# \* Kwitansi

# \* Foto

# \* Dokumen PDF

# \* Berita Acara

# 

# \---

# 

# \## Arsip

# 

# Seluruh SPJ yang telah selesai akan tersimpan sebagai arsip digital dan dapat dicari berdasarkan:

# 

# \* Tahun

# \* Bulan

# \* Nomor

# \* Program

# \* Kegiatan

# \* Rekanan

# 

# \---

# 

# \# Modul Laporan

# 

# \## Rekap SPJ

# 

# Menampilkan seluruh SPJ berdasarkan periode tertentu.

# 

# \---

# 

# \## Realisasi Anggaran

# 

# Menampilkan jumlah realisasi belanja berdasarkan Program, Kegiatan, maupun Sub Kegiatan.

# 

# \---

# 

# \## Rekap Rekanan

# 

# Menampilkan histori transaksi setiap rekanan.

# 

# \---

# 

# \## Rekap Pajak

# 

# Menampilkan total pajak yang dipotong berdasarkan periode.

# 

# \---

# 

# \# Audit Log

# 

# Seluruh aktivitas pengguna dicatat.

# 

# Contoh aktivitas:

# 

# \* Login

# \* Logout

# \* Menambah Data

# \* Mengubah Data

# \* Menghapus Data

# \* Mengunduh Dokumen

# \* Mencetak Dokumen

# \* Mengubah Status SPJ

# 

# Audit Log menyimpan:

# 

# \* User

# \* Waktu

# \* IP Address

# \* Browser

# \* Aktivitas

# \* Data Sebelum

# \* Data Sesudah

# 

# \---

# 

# \# Pengaturan

# 

# \## Penandatangan Aktif

# 

# Menentukan pejabat aktif yang digunakan sebagai default.

# 

# \---

# 

# \## Template Dokumen

# 

# Mengelola template:

# 

# \* Kwitansi

# \* Surat Pernyataan

# \* Berita Acara

# \* Daftar Pengeluaran

# \* Lampiran SPJ

# 

# \---

# 

# \## Nomor Otomatis

# 

# Format nomor dokumen dapat disesuaikan.

# 

# Contoh:

# 

# ```

# 001/SPJ/BPKAD/VII/2026

# ```

# 

# \---

# 

# \## Hak Akses

# 

# Role bawaan:

# 

# \* Administrator

# \* Bendahara

# \* PPTK

# \* PPK

# \* PA/KPA

# \* Auditor

# 

# Setiap role memiliki permission yang dapat diatur secara fleksibel.

# 

# \---

# 

# \# Workflow

# 

# ```

# Draft

# 

# ↓

# 

# Verifikasi PPTK

# 

# ↓

# 

# Verifikasi Bendahara

# 

# ↓

# 

# Persetujuan PA/KPA

# 

# ↓

# 

# Final

# 

# ↓

# 

# Arsip

# ```

# 

# \---

# 

# \# Keunggulan

# 

# \* Input sekali, seluruh dokumen otomatis terbentuk.

# \* Perhitungan nominal otomatis.

# \* Terbilang otomatis.

# \* Arsip digital.

# \* Audit Trail lengkap.

# \* Hak akses berbasis Role.

# \* Template dokumen dapat disesuaikan.

# \* Siap dikembangkan untuk integrasi dengan sistem pemerintah lainnya.

# 

# \---

# 

# \# Roadmap

# 

# \## Versi 1.0

# 

# \* Master Data

# \* SPJ

# \* Lampiran

# \* PDF

# \* Audit Log

# 

# \## Versi 1.5

# 

# \* Tanda Tangan Elektronik

# \* QR Code Verifikasi

# \* Approval Workflow

# \* Import Excel

# 

# \## Versi 2.0

# 

# \* Integrasi SIPD

# \* Integrasi e-Budgeting

# \* Dashboard Realisasi Anggaran

# \* API Mobile

# \* Notifikasi WhatsApp dan Email



