<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SPJ <?php echo e($spj->nomor_spj); ?></title>
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
    <div class="center muted">Nomor: <?php echo e($spj->nomor_spj); ?></div>

    <h2>Informasi SPJ</h2>
    <table class="meta">
        <tr><td style="width: 160px;">Tanggal</td><td>: <?php echo e($spj->tanggal?->translatedFormat('d F Y')); ?></td></tr>
        <tr><td>Bidang</td><td>: <?php echo e($spj->bidang?->nama ?? '-'); ?></td></tr>
        <tr><td>Program</td><td>: <?php echo e($spj->program?->nama ?? '-'); ?></td></tr>
        <tr><td>Kegiatan</td><td>: <?php echo e($spj->kegiatan?->nama ?? '-'); ?></td></tr>
        <tr><td>Sub Kegiatan</td><td>: <?php echo e($spj->subKegiatan?->nama ?? '-'); ?></td></tr>
        <tr><td>Rekening Belanja</td><td>: <?php echo e($spj->rekeningBelanja?->kode); ?> - <?php echo e($spj->rekeningBelanja?->nama); ?></td></tr>
        <tr><td>Status</td><td>: <?php echo e($spj->status->label()); ?></td></tr>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $spj->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($item->uraian); ?></td>
                    <td class="right"><?php echo e(number_format((float) $item->volume, 2, ',', '.')); ?> <?php echo e($item->satuan?->nama); ?></td>
                    <td class="right">Rp <?php echo e(number_format((float) $item->harga_satuan, 2, ',', '.')); ?></td>
                    <td class="right">Rp <?php echo e(number_format((float) $item->total, 2, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <tr>
                <th colspan="4" class="right">Total Belanja</th>
                <th class="right">Rp <?php echo e(number_format((float) $spj->total_belanja, 2, ',', '.')); ?></th>
            </tr>
        </tbody>
    </table>
    <p><strong>Terbilang:</strong> <?php echo e($spj->terbilang ?? '-'); ?></p>

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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $spj->buktiTransaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bukti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="center"><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($bukti->jenis); ?></td>
                    <td><?php echo e($bukti->nomor ?? '-'); ?></td>
                    <td><?php echo e($bukti->rekanan?->nama ?? '-'); ?></td>
                    <td class="right">Rp <?php echo e(number_format((float) $bukti->nominal, 2, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="center muted">Belum ada bukti transaksi.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                PPTK<br><br><br>
                <span class="signature-name"><?php echo e($spj->pptk?->nama ?? '-'); ?></span><br>
                NIP. <?php echo e($spj->pptk?->nip ?? '-'); ?>

            </td>
            <td>
                Bendahara<br><br><br>
                <span class="signature-name"><?php echo e($spj->bendahara?->nama ?? '-'); ?></span><br>
                NIP. <?php echo e($spj->bendahara?->nip ?? '-'); ?>

            </td>
            <td>
                PA/KPA<br><br><br>
                <span class="signature-name"><?php echo e($spj->paKpa?->nama ?? '-'); ?></span><br>
                NIP. <?php echo e($spj->paKpa?->nip ?? '-'); ?>

            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\SIPENA\resources\views/pdf/spj.blade.php ENDPATH**/ ?>