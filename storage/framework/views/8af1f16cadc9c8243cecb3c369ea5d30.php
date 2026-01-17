<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Daftar Ulang Seragam</title>
    <style>
        @page {
            margin: 10mm;
            size: A5 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .container {
            width: 100%;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            color: #111;
        }
        .header p {
            margin: 1px 0;
            font-size: 10px;
            color: #333;
        }
        .header .contact {
            font-size: 9px;
            color: #555;
        }
        .title {
            text-align: center;
            margin-bottom: 10px;
        }
        .title h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 2px 0;
            color: #111;
            text-transform: uppercase;
        }
        .title p {
            margin: 1px 0;
            color: #555;
        }
        .title .invoice-no {
            font-size: 10px;
            color: #333;
        }
        .title .date {
            font-size: 9px;
            color: #555;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        .layout-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .layout-table td {
            vertical-align: top;
        }
        .layout-table .left-col {
            width: 50%;
            padding-right: 10px;
        }
        .layout-table .right-col {
            width: 50%;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table {
            font-size: 10px;
            line-height: 1.4;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }
        .info-table .label {
            width: 32%;
            color: #333;
        }
        .info-table .separator {
            width: 2%;
            text-align: center;
        }
        .info-table .value {
            width: 66%;
            font-weight: bold;
            color: #000;
        }
        .detail-table {
            width: 100%;
            border: 1px solid #ccc;
            font-size: 10px;
        }
        .detail-table th {
            background-color: #f3f4f6;
            padding: 4px 8px;
            text-align: left;
            border: 1px solid #ccc;
            font-weight: bold;
            color: #111;
        }
        .detail-table td {
            padding: 4px 8px;
            border: 1px solid #ccc;
            color: #000;
        }
        .col-keterangan { width: 75%; }
        .col-jumlah { width: 25%; }
        .text-right {
            text-align: right;
        }
        .bg-gray {
            background-color: #f9fafb;
        }
        .size-box {
            background-color: #f9fafb;
            border: 1px solid #ccc;
            padding: 5px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2><?php echo e($printSettings['print_institution_name'] ?? 'NAMA LEMBAGA'); ?></h2>
            <p><?php echo e($printSettings['print_institution_address'] ?? 'Alamat Lembaga'); ?></p>
            <p class="contact">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($printSettings['print_institution_phone'])): ?> Telp: <?php echo e($printSettings['print_institution_phone']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($printSettings['print_institution_email'])): ?> | Email: <?php echo e($printSettings['print_institution_email']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>

        <!-- Title -->
        <div class="title">
            <h3>INVOICE DAFTAR ULANG SERAGAM</h3>
            <p class="invoice-no">No: <?php echo e(str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), str_pad($seragam->id, 3, '0', STR_PAD_LEFT)], $invoiceSettings['nomor_format'] ?? 'INV-SRG-{TAHUN}-{NOMOR}')); ?></p>
            <p class="date">Tanggal: <?php echo e(now()->format('d/m/Y')); ?></p>
        </div>

        <!-- Info Section (Side by Side) -->
        <table class="layout-table">
            <tr>
                <td class="left-col">
                    <div class="section-title">Informasi Pendaftar:</div>
                    <table class="info-table">
                        <tr>
                            <td class="label">No. Pendaftaran</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->nomor_pendaftaran); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Nama</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->nama_lengkap); ?></td>
                        </tr>
                        <tr>
                            <td class="label">NISN</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->nisn); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Jenis Kelamin</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Jalur Pendaftaran</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->jalurPendaftaran->nama ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Asal Sekolah</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($pendaftar->asal_sekolah); ?></td>
                        </tr>
                    </table>
                </td>
                <td class="right-col">
                    <div class="section-title">Informasi Pembayaran:</div>
                    <table class="info-table">
                        <tr>
                            <td class="label" style="width: 30%;">Bank Dari</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($bank['name'] ?? 'Bank'); ?> <?php echo e(isset($bank['account_number']) ? '- ' . $bank['account_number'] : ''); ?></td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Waktu Transfer</td>
                            <td class="separator">:</td>
                            <td class="value"><?php echo e($seragam->updated_at ? $seragam->updated_at->format('d/m/Y H:i') . ' WIB' : '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Status</td>
                            <td class="separator">:</td>
                            <td class="value" style="color: green; text-transform: uppercase;">
                                <?php echo e($seragam->status_seragam == 'disetujui' ? 'LUNAS / DISETUJUI' : 'MENUNGGU VERIFIKASI'); ?>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Uniform Size Info -->
        <div class="section">
            <div class="section-title">Ukuran Seragam & Atribut:</div>
            <div class="size-box">
                <table class="info-table">
                    <tr>
                        <td style="width: 25%">Ukuran Jas</td>
                        <td class="value" style="width: 25%">: <?php echo e($seragam->ukuran_baju ?? '-'); ?></td>
                        <td style="width: 25%">Ukuran Atasan/Baju</td>
                        <td class="value" style="width: 25%">: <?php echo e($seragam->ukuran_baju ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td>Ukuran Bawahan</td>
                        <td class="value">: <?php echo e($seragam->ukuran_celana ?? '-'); ?></td>
                        <td>Ukuran Kopyah</td>
                        <td class="value">: <?php echo e($seragam->ukuran_topi ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Biaya -->
        <div class="section">
            <div class="section-title">Detail Biaya:</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="col-keterangan">Keterangan</th>
                        <th class="col-jumlah text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($item['name'] ?? '-'); ?></td>
                        <td class="text-right">Rp <?php echo e(number_format($item['amount'] ?? 0, 0, ',', '.')); ?></td>
                    </tr>
                    <?php $total += ($item['amount'] ?? 0); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr class="bg-gray">
                        <td style="font-weight: bold;">Total</td>
                        <td class="text-right" style="font-weight: bold;">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <?php echo e(!empty($invoiceSettings['footer']) ? $invoiceSettings['footer'] : 'Terima kasih atas kepercayaan Anda'); ?>

        </div>
    </div>
</body>
</html>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\pdf\invoice-daftar-ulang-seragam.blade.php ENDPATH**/ ?>