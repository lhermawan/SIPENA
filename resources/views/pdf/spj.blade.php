<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SPJ {{ $spj->nomor_spj }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 4px; text-align: center; }
        h2 { font-size: 13px; margin: 18px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .meta td { border: 0; padding: 3px 0; }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { color: #6b7280; }
        .signatures { margin-top: 32px; }
        .signatures td { border: 0; text-align: center; height: 88px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <h1>SURAT PERTANGGUNGJAWABAN</h1>
    <div class="center muted">Nomor: {{ $spj->nomor_spj }}</div>

    <h2>Informasi SPJ</h2>
    <table class="meta">
        <tr><td style="width: 160px;">Tanggal</td><td>: {{ $spj->tanggal?->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Bidang</td><td>: {{ $spj->bidang?->nama ?? '-' }}</td></tr>
        <tr><td>Program</td><td>: {{ $spj->program?->nama ?? '-' }}</td></tr>
        <tr><td>Kegiatan</td><td>: {{ $spj->kegiatan?->nama ?? '-' }}</td></tr>
        <tr><td>Sub Kegiatan</td><td>: {{ $spj->subKegiatan?->nama ?? '-' }}</td></tr>
        <tr><td>Rekening Belanja</td><td>: {{ $spj->rekeningBelanja?->kode }} - {{ $spj->rekeningBelanja?->nama }}</td></tr>
        <tr><td>Status</td><td>: {{ $spj->status->label() }}</td></tr>
    </table>

    <h2>Rincian Belanja</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th>Uraian</th>
                <th style="width: 70px;">Volume</th>
                <th style="width: 90px;">Harga</th>
                <th style="width: 100px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($spj->items as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->uraian }}</td>
                    <td class="right">{{ number_format((float) $item->volume, 2, ',', '.') }} {{ $item->satuan?->nama }}</td>
                    <td class="right">Rp {{ number_format((float) $item->harga_satuan, 2, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format((float) $item->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="4" class="right">Total Belanja</th>
                <th class="right">Rp {{ number_format((float) $spj->total_belanja, 2, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
    <p><strong>Terbilang:</strong> {{ $spj->terbilang ?? '-' }}</p>

    <h2>Bukti Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th>Jenis</th>
                <th>Nomor</th>
                <th>Rekanan</th>
                <th style="width: 100px;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($spj->buktiTransaksis as $bukti)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $bukti->jenis }}</td>
                    <td>{{ $bukti->nomor ?? '-' }}</td>
                    <td>{{ $bukti->rekanan?->nama ?? '-' }}</td>
                    <td class="right">Rp {{ number_format((float) $bukti->nominal, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="center muted">Belum ada bukti transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                PPTK<br><br><br>
                <span class="signature-name">{{ $spj->pptk?->nama ?? '-' }}</span><br>
                NIP. {{ $spj->pptk?->nip ?? '-' }}
            </td>
            <td>
                Bendahara<br><br><br>
                <span class="signature-name">{{ $spj->bendahara?->nama ?? '-' }}</span><br>
                NIP. {{ $spj->bendahara?->nip ?? '-' }}
            </td>
            <td>
                PA/KPA<br><br><br>
                <span class="signature-name">{{ $spj->paKpa?->nama ?? '-' }}</span><br>
                NIP. {{ $spj->paKpa?->nip ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
