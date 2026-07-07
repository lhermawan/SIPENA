<?php

namespace Database\Seeders;

use App\Enums\SpjStatus;
use App\Models\Bidang;
use App\Models\BuktiTransaksi;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Penandatangan;
use App\Models\Program;
use App\Models\Rekanan;
use App\Models\RekeningBelanja;
use App\Models\Satuan;
use App\Models\Spj;
use App\Models\SpjItem;
use App\Models\SubKegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Administrator', 'Bendahara', 'PPTK', 'PPK', 'PA/KPA', 'Auditor'];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        Role::findOrCreate('Bidang');

        foreach (['Paket', 'Unit', 'Orang', 'Hari', 'Bulan', 'Lembar', 'Dokumen', 'Kegiatan', 'Rim'] as $nama) {
            Satuan::firstOrCreate(['nama' => $nama]);
        }

        $this->seedUsers($roles);
        $this->seedBidangUsers();
        $this->seedMasterData();
        $this->seedSupportTables();
        $this->seedTransactions();
    }

    private function seedUsers(array $roles): void
    {
        foreach ($roles as $role) {
            $email = strtolower(str_replace(['/', ' '], ['-', '.'], $role)).'@sipena.local';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $role.' SIPENA',
                    'password' => Hash::make('password'),
                ],
            );

            $user->syncRoles([$role]);
        }

        foreach (['admin@sipena.local', 'admin@sipena.test'] as $email) {
            $admin = User::updateOrCreate(
                ['email' => $email],
                ['name' => 'Administrator SIPENA', 'password' => Hash::make('password')],
            );

            $admin->syncRoles(['Administrator']);
        }
    }

    private function seedBidangUsers(): void
    {
        foreach ([
            ['nama' => 'Aptika', 'slug' => 'aptika', 'email' => 'aptika@sipena.local', 'name' => 'Bidang Aptika'],
            ['nama' => 'Persandian', 'slug' => 'persandian', 'email' => 'persandian@sipena.local', 'name' => 'Bidang Persandian'],
            ['nama' => 'IKP', 'slug' => 'ikp', 'email' => 'ikp@sipena.local', 'name' => 'Bidang IKP'],
            ['nama' => 'Statistik', 'slug' => 'statistik', 'email' => 'statistik@sipena.local', 'name' => 'Bidang Statistik'],
            ['nama' => 'Sekretariat', 'slug' => 'sekretariat', 'email' => 'sekretariat@sipena.local', 'name' => 'Sekretariat'],
        ] as $bidang) {
            $bidangModel = Bidang::updateOrCreate(
                ['slug' => $bidang['slug']],
                ['nama' => $bidang['nama'], 'is_active' => true],
            );

            $user = User::updateOrCreate(
                ['email' => $bidang['email']],
                [
                    'name' => $bidang['name'],
                    'password' => Hash::make('password'),
                ],
            );

            $user->syncRoles(['Bidang']);
            $user->bidangs()->syncWithoutDetaching([$bidangModel->id]);
        }
    }

    private function seedMasterData(): void
    {
        $pegawais = [
            ['nama' => 'Rina Pramesti', 'nip' => '198104122006042001', 'pangkat' => 'Pembina', 'golongan' => 'IV/a', 'jabatan' => 'Kepala Badan', 'unit_kerja' => 'BPKAD'],
            ['nama' => 'Dedi Saputra', 'nip' => '198307192009031004', 'pangkat' => 'Penata Tk. I', 'golongan' => 'III/d', 'jabatan' => 'Sekretaris Badan', 'unit_kerja' => 'BPKAD'],
            ['nama' => 'Maya Lestari', 'nip' => '198905142011012008', 'pangkat' => 'Penata', 'golongan' => 'III/c', 'jabatan' => 'Kasubbag Keuangan', 'unit_kerja' => 'BPKAD'],
            ['nama' => 'Ahmad Fauzi', 'nip' => '199102062014031003', 'pangkat' => 'Penata Muda Tk. I', 'golongan' => 'III/b', 'jabatan' => 'Bendahara Pengeluaran', 'unit_kerja' => 'BPKAD'],
            ['nama' => 'Siti Rahmawati', 'nip' => '199306212019022005', 'pangkat' => 'Penata Muda', 'golongan' => 'III/a', 'jabatan' => 'Analis Keuangan', 'unit_kerja' => 'BPKAD'],
        ];

        foreach ($pegawais as $pegawai) {
            Pegawai::updateOrCreate(['nip' => $pegawai['nip']], $pegawai + ['is_active' => true]);
        }

        foreach ([
            ['nip' => '198104122006042001', 'peran' => 'PA/KPA'],
            ['nip' => '198307192009031004', 'peran' => 'PPK'],
            ['nip' => '198905142011012008', 'peran' => 'PPTK'],
            ['nip' => '199102062014031003', 'peran' => 'Bendahara'],
            ['nip' => '198104122006042001', 'peran' => 'Pengguna Anggaran'],
        ] as $data) {
            $pegawai = Pegawai::where('nip', $data['nip'])->first();

            Penandatangan::updateOrCreate(
                ['nip' => $pegawai->nip, 'peran' => $data['peran']],
                [
                    'pegawai_id' => $pegawai->id,
                    'nama' => $pegawai->nama,
                    'jabatan' => $data['peran'] === 'PA/KPA' ? 'Kepala BPKAD selaku PA/KPA' : $pegawai->jabatan,
                    'is_active' => true,
                ],
            );
        }

        foreach ([
            ['nama' => 'CV Maju Bersama', 'npwp' => '01.234.567.8-901.000', 'alamat' => 'Jl. Merdeka No. 10, Jakarta', 'nomor_rekening' => '1234567890', 'nama_bank' => 'Bank DKI', 'kontak' => '0812-1000-2000'],
            ['nama' => 'PT Sinar Digital Nusantara', 'npwp' => '02.345.678.9-012.000', 'alamat' => 'Jl. Gatot Subroto No. 25, Jakarta', 'nomor_rekening' => '9876543210', 'nama_bank' => 'Bank Mandiri', 'kontak' => '0813-2000-3000'],
            ['nama' => 'UD Karya ATK', 'npwp' => '03.456.789.0-123.000', 'alamat' => 'Jl. Cempaka No. 7, Depok', 'nomor_rekening' => '5566778899', 'nama_bank' => 'BRI', 'kontak' => '0814-3000-4000'],
        ] as $rekanan) {
            Rekanan::updateOrCreate(['npwp' => $rekanan['npwp']], $rekanan);
        }

        foreach ([
            ['kode' => '5.1.02.01.01.0024', 'nama' => 'Belanja Alat/Bahan untuk Kegiatan Kantor - Alat Tulis Kantor'],
            ['kode' => '5.1.02.01.01.0052', 'nama' => 'Belanja Makanan dan Minuman Rapat'],
            ['kode' => '5.1.02.02.01.0003', 'nama' => 'Honorarium Narasumber atau Pembahas'],
            ['kode' => '5.1.02.02.01.0029', 'nama' => 'Belanja Jasa Tenaga Ahli'],
            ['kode' => '5.1.02.04.01.0001', 'nama' => 'Belanja Perjalanan Dinas Biasa'],
        ] as $rekening) {
            RekeningBelanja::updateOrCreate(['kode' => $rekening['kode']], $rekening);
        }

        foreach ($this->programData() as $programData) {
            $program = Program::updateOrCreate(
                ['kode' => $programData['kode']],
                ['nama' => $programData['nama']],
            );

            foreach ($programData['kegiatans'] as $kegiatanData) {
                $kegiatan = Kegiatan::updateOrCreate(
                    ['program_id' => $program->id, 'kode' => $kegiatanData['kode']],
                    ['nama' => $kegiatanData['nama']],
                );

                foreach ($kegiatanData['sub_kegiatans'] as $subKegiatanData) {
                    SubKegiatan::updateOrCreate(
                        ['kegiatan_id' => $kegiatan->id, 'kode' => $subKegiatanData['kode']],
                        ['nama' => $subKegiatanData['nama']],
                    );
                }
            }
        }
    }

    private function seedSupportTables(): void
    {
        $now = now();

        foreach ([
            ['kode' => 'SPJ', 'nama' => 'Nomor SPJ', 'prefix' => null, 'suffix' => '/SPJ/BPKAD', 'digit' => 3, 'nomor_terakhir' => 5, 'tahun' => (int) date('Y'), 'bulan' => null, 'reset_tahunan' => true, 'reset_bulanan' => false],
            ['kode' => 'ARSIP', 'nama' => 'Nomor Arsip SPJ', 'prefix' => 'ARS', 'suffix' => '/BPKAD', 'digit' => 4, 'nomor_terakhir' => 2, 'tahun' => (int) date('Y'), 'bulan' => null, 'reset_tahunan' => true, 'reset_bulanan' => false],
        ] as $nomor) {
            DB::table('nomor_otomatis')->updateOrInsert(
                ['kode' => $nomor['kode']],
                $nomor + ['is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            );
        }

        foreach ([
            ['kode' => 'SPJ-STANDAR', 'nama' => 'Template SPJ Standar', 'jenis' => 'spj', 'variabel' => ['nomor_spj', 'tanggal', 'program', 'kegiatan', 'total_belanja']],
            ['kode' => 'KWITANSI', 'nama' => 'Template Kwitansi Pembayaran', 'jenis' => 'bukti_transaksi', 'variabel' => ['nomor', 'tanggal', 'rekanan', 'nominal', 'keterangan']],
            ['kode' => 'BAST', 'nama' => 'Template Berita Acara Serah Terima', 'jenis' => 'bast', 'variabel' => ['nomor_spj', 'rekanan', 'pekerjaan', 'tanggal']],
        ] as $template) {
            DB::table('template_dokumens')->updateOrInsert(
                ['kode' => $template['kode']],
                [
                    'nama' => $template['nama'],
                    'jenis' => $template['jenis'],
                    'file_path' => null,
                    'variabel' => json_encode($template['variabel']),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        foreach ([
            ['group' => 'umum', 'key' => 'nama_instansi', 'value' => 'Badan Pengelola Keuangan dan Aset Daerah', 'type' => 'string', 'description' => 'Nama instansi yang ditampilkan pada dokumen.'],
            ['group' => 'umum', 'key' => 'singkatan_instansi', 'value' => 'BPKAD', 'type' => 'string', 'description' => 'Singkatan instansi untuk nomor dokumen.'],
            ['group' => 'dokumen', 'key' => 'kota_ttd', 'value' => 'Jakarta', 'type' => 'string', 'description' => 'Kota penandatanganan dokumen.'],
            ['group' => 'dokumen', 'key' => 'format_nomor_spj', 'value' => '{urut}/SPJ/{unit}/{bulan_romawi}/{tahun}', 'type' => 'string', 'description' => 'Format default nomor SPJ.'],
        ] as $setting) {
            DB::table('pengaturan_aplikasi')->updateOrInsert(
                ['key' => $setting['key']],
                $setting + ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }

    private function seedTransactions(): void
    {
        $pptk = Penandatangan::where('peran', 'PPTK')->first();
        $ppk = Penandatangan::where('peran', 'PPK')->first();
        $bendahara = Penandatangan::where('peran', 'Bendahara')->first();
        $paKpa = Penandatangan::where('peran', 'PA/KPA')->first();
        $rekanans = Rekanan::orderBy('id')->get();

        $examples = [
            [
                'nomor_spj' => '001/SPJ/BPKAD/VII/2026',
                'tanggal' => '2026-07-01',
                'program_kode' => '5.02.01',
                'kegiatan_kode' => '5.02.01.2.01',
                'sub_kegiatan_kode' => '5.02.01.2.01.0001',
                'rekening_kode' => '5.1.02.01.01.0024',
                'status' => SpjStatus::Draft,
                'items' => [
                    ['uraian' => 'Pembelian kertas HVS A4 80 gsm', 'volume' => 20, 'satuan' => 'Rim', 'harga_satuan' => 65000],
                    ['uraian' => 'Pembelian map arsip dan ordner kegiatan', 'volume' => 15, 'satuan' => 'Paket', 'harga_satuan' => 85000],
                ],
                'bukti' => ['jenis' => 'Faktur', 'nomor' => 'FKT-ATK-0701', 'rekanan_index' => 2],
            ],
            [
                'nomor_spj' => '002/SPJ/BPKAD/VII/2026',
                'tanggal' => '2026-07-02',
                'program_kode' => '5.02.02',
                'kegiatan_kode' => '5.02.02.2.02',
                'sub_kegiatan_kode' => '5.02.02.2.02.0002',
                'rekening_kode' => '5.1.02.01.01.0052',
                'status' => SpjStatus::VerifikasiBendahara,
                'items' => [
                    ['uraian' => 'Konsumsi rapat koordinasi penyusunan laporan keuangan', 'volume' => 35, 'satuan' => 'Orang', 'harga_satuan' => 45000],
                    ['uraian' => 'Snack rapat koordinasi perangkat daerah', 'volume' => 35, 'satuan' => 'Orang', 'harga_satuan' => 25000],
                ],
                'bukti' => ['jenis' => 'Kwitansi', 'nomor' => 'KW-0702-001', 'rekanan_index' => 0],
            ],
            [
                'nomor_spj' => '003/SPJ/BPKAD/VII/2026',
                'tanggal' => '2026-07-03',
                'program_kode' => '5.02.03',
                'kegiatan_kode' => '5.02.03.2.01',
                'sub_kegiatan_kode' => '5.02.03.2.01.0001',
                'rekening_kode' => '5.1.02.02.01.0003',
                'status' => SpjStatus::Final,
                'items' => [
                    ['uraian' => 'Honorarium narasumber sosialisasi penatausahaan aset', 'volume' => 2, 'satuan' => 'Orang', 'harga_satuan' => 750000],
                    ['uraian' => 'Penggandaan materi sosialisasi', 'volume' => 50, 'satuan' => 'Dokumen', 'harga_satuan' => 18000],
                ],
                'bukti' => ['jenis' => 'Daftar Honorarium', 'nomor' => 'HON-ASET-0703', 'rekanan_index' => 1],
            ],
        ];

        foreach ($examples as $example) {
            $program = Program::where('kode', $example['program_kode'])->firstOrFail();
            $kegiatan = Kegiatan::where('program_id', $program->id)->where('kode', $example['kegiatan_kode'])->firstOrFail();
            $subKegiatan = SubKegiatan::where('kegiatan_id', $kegiatan->id)->where('kode', $example['sub_kegiatan_kode'])->firstOrFail();
            $rekening = RekeningBelanja::where('kode', $example['rekening_kode'])->firstOrFail();

            $totalBelanja = collect($example['items'])->sum(fn (array $item): float => $item['volume'] * $item['harga_satuan']);

            $spj = Spj::updateOrCreate(
                ['nomor_spj' => $example['nomor_spj']],
                [
                    'tanggal' => $example['tanggal'],
                    'program_id' => $program->id,
                    'kegiatan_id' => $kegiatan->id,
                    'sub_kegiatan_id' => $subKegiatan->id,
                    'rekening_belanja_id' => $rekening->id,
                    'pptk_id' => $pptk?->id,
                    'ppk_id' => $ppk?->id,
                    'bendahara_id' => $bendahara?->id,
                    'pa_kpa_id' => $paKpa?->id,
                    'status' => $example['status']->value,
                    'total_belanja' => $totalBelanja,
                    'terbilang' => $this->demoTerbilang($totalBelanja),
                    'finalized_at' => $example['status'] === SpjStatus::Final ? Carbon::parse($example['tanggal'])->endOfDay() : null,
                ],
            );

            SpjItem::where('spj_id', $spj->id)->delete();

            foreach ($example['items'] as $item) {
                $satuan = Satuan::where('nama', $item['satuan'])->firstOrFail();

                SpjItem::create([
                    'spj_id' => $spj->id,
                    'uraian' => $item['uraian'],
                    'volume' => $item['volume'],
                    'satuan_id' => $satuan->id,
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $item['volume'] * $item['harga_satuan'],
                ]);
            }

            BuktiTransaksi::updateOrCreate(
                ['spj_id' => $spj->id, 'nomor' => $example['bukti']['nomor']],
                [
                    'rekanan_id' => $rekanans->get($example['bukti']['rekanan_index'])?->id,
                    'jenis' => $example['bukti']['jenis'],
                    'tanggal' => $example['tanggal'],
                    'nominal' => $totalBelanja,
                    'keterangan' => 'Bukti transaksi demo untuk '.$spj->nomor_spj,
                ],
            );

            if ($example['status'] === SpjStatus::Final) {
                DB::table('arsips')->updateOrInsert(
                    ['nomor_arsip' => 'ARS-'.str_replace('/', '-', $spj->nomor_spj)],
                    [
                        'spj_id' => $spj->id,
                        'tanggal_arsip' => Carbon::parse($example['tanggal'])->addDay()->toDateString(),
                        'kategori' => 'SPJ Final',
                        'lokasi_fisik' => 'Lemari Arsip BPKAD/Rak 01',
                        'keterangan' => 'Arsip demo untuk SPJ final.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }

    private function programData(): array
    {
        return [
            [
                'kode' => '5.02.01',
                'nama' => 'Program Penunjang Urusan Pemerintahan Daerah',
                'kegiatans' => [
                    [
                        'kode' => '5.02.01.2.01',
                        'nama' => 'Perencanaan, Penganggaran, dan Evaluasi Kinerja Perangkat Daerah',
                        'sub_kegiatans' => [
                            ['kode' => '5.02.01.2.01.0001', 'nama' => 'Penyusunan Dokumen Perencanaan Perangkat Daerah'],
                            ['kode' => '5.02.01.2.01.0002', 'nama' => 'Evaluasi Kinerja Perangkat Daerah'],
                        ],
                    ],
                    [
                        'kode' => '5.02.01.2.02',
                        'nama' => 'Administrasi Keuangan Perangkat Daerah',
                        'sub_kegiatans' => [
                            ['kode' => '5.02.01.2.02.0001', 'nama' => 'Penyediaan Gaji dan Tunjangan ASN'],
                            ['kode' => '5.02.01.2.02.0002', 'nama' => 'Pelaksanaan Penatausahaan dan Pengujian Keuangan SKPD'],
                        ],
                    ],
                ],
            ],
            [
                'kode' => '5.02.02',
                'nama' => 'Program Pengelolaan Keuangan Daerah',
                'kegiatans' => [
                    [
                        'kode' => '5.02.02.2.01',
                        'nama' => 'Koordinasi dan Penyusunan Rencana Anggaran Daerah',
                        'sub_kegiatans' => [
                            ['kode' => '5.02.02.2.01.0001', 'nama' => 'Koordinasi Penyusunan KUA dan PPAS'],
                            ['kode' => '5.02.02.2.01.0002', 'nama' => 'Koordinasi Penyusunan APBD'],
                        ],
                    ],
                    [
                        'kode' => '5.02.02.2.02',
                        'nama' => 'Koordinasi dan Pengelolaan Perbendaharaan Daerah',
                        'sub_kegiatans' => [
                            ['kode' => '5.02.02.2.02.0001', 'nama' => 'Koordinasi Penatausahaan Kas Daerah'],
                            ['kode' => '5.02.02.2.02.0002', 'nama' => 'Rekonsiliasi Data Penerimaan dan Pengeluaran Kas'],
                        ],
                    ],
                ],
            ],
            [
                'kode' => '5.02.03',
                'nama' => 'Program Pengelolaan Barang Milik Daerah',
                'kegiatans' => [
                    [
                        'kode' => '5.02.03.2.01',
                        'nama' => 'Pengelolaan Barang Milik Daerah',
                        'sub_kegiatans' => [
                            ['kode' => '5.02.03.2.01.0001', 'nama' => 'Penyusunan Standar Harga Barang dan Jasa'],
                            ['kode' => '5.02.03.2.01.0002', 'nama' => 'Pengamanan Barang Milik Daerah'],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function demoTerbilang(float $amount): string
    {
        return match ((int) $amount) {
            2575000 => 'Dua juta lima ratus tujuh puluh lima ribu rupiah',
            2450000 => 'Dua juta empat ratus lima puluh ribu rupiah',
            2400000 => 'Dua juta empat ratus ribu rupiah',
            default => 'Dua juta empat ratus ribu rupiah',
        };
    }
}
