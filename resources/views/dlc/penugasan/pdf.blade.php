<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran &amp; Penugasan Training</title>
    <style>
        @page {
            margin: 8mm 10mm 8mm 10mm;
            size: a4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .fst-italic { font-style: italic; }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-logo {
            width: 180px;
            vertical-align: middle;
        }
        .header-title {
            text-align: right;
            vertical-align: middle;
            font-size: 13pt;
            font-weight: bold;
        }

        /* Top Meta Table */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 110px;
            font-weight: bold;
        }
        .meta-colon {
            width: 12px;
            text-align: center;
        }
        .meta-value {
            border-bottom: 1px dotted #555;
            padding-bottom: 1px;
        }

        /* Participants Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
            font-size: 8pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 3px 4px;
        }
        .data-table th {
            background-color: #00b0f0;
            color: #000;
            font-weight: bold;
            text-align: center;
        }

        /* Biaya Table */
        .biaya-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 8.5pt;
        }
        .biaya-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .biaya-label {
            width: 170px;
            font-weight: normal;
        }
        .biaya-colon {
            width: 12px;
            text-align: center;
        }
        .biaya-value {
            border-bottom: 1px dotted #555;
        }

        /* Box Alasan */
        .box-alasan {
            border: 1.5px solid #000;
            padding: 4px 6px;
            margin-bottom: 6px;
            min-height: 42px;
            font-size: 8.5pt;
        }

        /* Data Persetujuan Divisi */
        .divisi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 8.5pt;
        }
        .divisi-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .divisi-label {
            width: 170px;
        }
        .divisi-colon {
            width: 12px;
            text-align: center;
        }
        .divisi-value {
            border-bottom: 1px dotted #555;
        }

        /* Info & Ketentuan Box */
        .info-ketentuan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 7.5pt;
        }
        .info-ketentuan-table th {
            background-color: #00b0f0;
            border: 1px solid #000;
            padding: 2.5px 4px;
            text-align: center;
            font-weight: bold;
        }
        .info-ketentuan-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
        }

        /* Banners */
        .banner-cyan {
            background-color: #00b0f0;
            border: 1px solid #000;
            padding: 2.5px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            margin-bottom: 0;
        }
        .banner-content {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            margin-bottom: 6px;
            min-height: 18px;
        }

        /* Persetujuan Table (Signatures) */
        .persetujuan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        .persetujuan-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            width: 50%;
        }

        .statement-text {
            font-size: 7.5pt;
            text-align: justify;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .sign-space {
            height: 48px;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .footer-accent {
            background-color: #00b0f0;
            height: 8px;
        }
        .footer-code {
            text-align: right;
            font-size: 7.5pt;
            padding-top: 2px;
        }
        .footer-box {
            border: 1px solid #000;
            padding: 1px 6px;
            display: inline-block;
            font-size: 7.5pt;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT / HEADER --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="vertical-align: middle; padding-right: 6px;">
                            <span style="font-size: 26pt; font-weight: 900; color: #005a9c; letter-spacing: -1px; font-family: 'Arial Black', Arial, sans-serif;">D<span style="color: #00b0f0;">L</span><span style="color: #005a9c;">C</span></span>
                        </td>
                        <td style="vertical-align: middle; border-left: 1.5px solid #005a9c; padding-left: 6px;">
                            <div style="font-size: 8pt; font-weight: 900; color: #000; line-height: 1.1;">DHARMA</div>
                            <div style="font-size: 8pt; font-weight: 900; color: #000; line-height: 1.1;">LEARNING</div>
                            <div style="font-size: 8pt; font-weight: 900; color: #000; line-height: 1.1;">CENTER</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 6.5pt; font-style: italic; color: #333; padding-top: 1px;">
                            "Develop People to be Excellence"
                        </td>
                    </tr>
                </table>
            </td>
            <td class="header-title">
                Formulir Pendaftaran &amp; Penugasan Training
            </td>
        </tr>
    </table>

    {{-- TOP META INFORMATION --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Training</td>
            <td class="meta-colon">:</td>
            <td class="meta-value"><strong>{{ $penugasan->nama_training }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Jenis Training</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $penugasan->jenis_training }}</td>
        </tr>
        <tr>
            <td class="meta-label">SubCo</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $penugasan->sub_co }}</td>
        </tr>
        <tr>
            <td class="meta-label">Divisi</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $penugasan->divisi }}</td>
        </tr>
    </table>

    {{-- TABEL PESERTA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 65px;">NPK</th>
                <th>Nama lengkap Peserta</th>
                <th style="width: 100px;">Bagian</th>
                <th style="width: 80px;">Jabatan</th>
                <th style="width: 120px;">Atasan Langsung</th>
                <th style="width: 65px;">Paraf Atasan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pesertaList = $penugasan->peserta;
                // Pad to at least 3 rows to look consistent with official format
                $displayRows = $pesertaList;
                while (count($displayRows) < 3) {
                    $displayRows[] = ['npk' => '', 'nama' => '', 'bagian' => '', 'jabatan' => '', 'atasan' => '', 'paraf' => ''];
                }
            @endphp
            @foreach($displayRows as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ $p['npk'] ?? '' }}</td>
                <td>{{ $p['nama'] ?? '' }}</td>
                <td>{{ $p['bagian'] ?? '' }}</td>
                <td class="text-center">{{ $p['jabatan'] ?? '' }}</td>
                <td>{{ $p['atasan'] ?? '' }}</td>
                <td class="text-center">{{ $p['paraf'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="font-size: 7pt; margin-bottom: 4px;">* Mohon data peserta diisi dengan lengkap</div>

    {{-- BIAYA & PESERTA --}}
    <table class="biaya-table">
        <tr>
            <td class="biaya-label">Jumlah Peserta</td>
            <td class="biaya-colon">:</td>
            <td class="biaya-value">{{ $penugasan->jumlah_peserta }} Orang</td>
        </tr>
        <tr>
            <td class="biaya-label">Biaya Investasi per Peserta*</td>
            <td class="biaya-colon">:</td>
            <td class="biaya-value">Rp {{ number_format($penugasan->biaya_per_peserta, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="biaya-label">Total Biaya Investasi*</td>
            <td class="biaya-colon">:</td>
            <td class="biaya-value"><strong>Rp {{ number_format($penugasan->total_biaya, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="biaya-label">Terbilang</td>
            <td class="biaya-colon">:</td>
            <td class="biaya-value"><em>{{ $penugasan->terbilang ?: '-' }}</em></td>
        </tr>
    </table>
    <div style="font-size: 7.5pt; font-weight: bold; font-style: italic; margin-bottom: 5px;">*)Note : Biaya Ditanggung Perusahaan</div>

    {{-- KOTAK ALASAN MENGIKUTI PELATIHAN --}}
    <div class="box-alasan">
        <div style="font-weight: bold; margin-bottom: 2px;">Alasan Mengikuti Pelatihan :</div>
        <div>{{ $penugasan->alasan_pelatihan ?: '-' }}</div>
    </div>

    {{-- DATA PERSETUJUAN DIVISI --}}
    <div style="font-size: 8pt; font-weight: bold; text-decoration: underline; margin-bottom: 2px;">Data Persetujuan Divisi</div>
    <table class="divisi-table">
        <tr>
            <td class="divisi-label">Nama Atasan (Div/Dept)</td>
            <td class="divisi-colon">:</td>
            <td class="divisi-value">{{ $penugasan->nama_atasan ?: '-' }}</td>
        </tr>
        <tr>
            <td class="divisi-label">Divisi</td>
            <td class="divisi-colon">:</td>
            <td class="divisi-value">{{ $penugasan->divisi_atasan ?: ($penugasan->divisi ?: '-') }}</td>
        </tr>
        <tr>
            <td class="divisi-label">Jabatan</td>
            <td class="divisi-colon">:</td>
            <td class="divisi-value">{{ $penugasan->jabatan_atasan ?: '-' }}</td>
        </tr>
    </table>

    {{-- INFORMASI PENDAFTARAN & KETENTUAN --}}
    <table class="info-ketentuan-table">
        <thead>
            <tr>
                <th style="width: 45%;">INFORMASI PENDAFTARAN</th>
                <th style="width: 55%;">KETENTUAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold; margin-bottom: 2px;">Dharma Learning Center</div>
                    <table style="width: 100%; border-collapse: collapse; border: none; font-size: 7.5pt;">
                        <tr><td style="width: 45px; border: none; padding: 1px 0;">Ext</td><td style="width: 8px; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">402</td></tr>
                        <tr><td style="border: none; padding: 1px 0;">e-mail</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">learning.center@dp.dharmap.com</td></tr>
                        <tr><td style="border: none; padding: 1px 0;">Web</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">dharmagroup.co.id</td></tr>
                    </table>
                </td>
                <td>
                    <ol style="margin: 0; padding-left: 14px;">
                        <li>Peserta Wajib hadir sesuai dengan jadwal yang telah ditentukan</li>
                        <li>Bagi peserta yang tidak hadir Wajib mengisi &amp; mengumpulkan Form Berita Acara Ketidakhadiran paling lambat H-3 sebelum pelaksanaan</li>
                        <li>Jika peserta tidak hadir dan belum mengumpulkan Form berita Acara pada H-3, maka biaya investasi akan di bebankan kepada masing - masing peserta &amp; atasan peserta</li>
                        <li>Invoice akan dikirimkan langsung ke masing - masing SubCo melalui PIC Finance PT. Dharma Polimetal Tbk</li>
                    </ol>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TEMPAT & TANGGAL PENYELENGGARAAN TRAINING --}}
    <div class="banner-cyan">TEMPAT &amp; TANGGAL PENYELENGGARAAN TRAINING</div>
    <div class="banner-content">
        {{ $penugasan->tempat_tanggal_training ?: '-' }}
    </div>

    {{-- PERSETUJUAN --}}
    <div class="banner-cyan">PERSETUJUAN</div>
    <table class="persetujuan-table">
        <tr>
            <td>
                <div class="statement-text">
                    Bersama ini kami konfirmasikan pendaftaran nama tersebut di atas dan menyetujui semua ketentuan yang berlaku.<br>
                    Serta melalui dokumen ini, kami tugaskan nama yang bersangkutan untuk dapat mengikuti kegiatan sesuai dengan jadwal yang telah di tentukan melalui PIC Training
                </div>
                <div style="margin-bottom: 2px;">{{ $penugasan->tempat_tanggal_persetujuan ?: 'Cikarang, ' . date('d F Y') }}</div>
                <div style="margin-bottom: 2px;">Disetujui,</div>
                <div class="sign-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">
                    {{ $penugasan->penyetujui_nama ?: 'Director' }}
                </div>
                @if($penugasan->penyetujui_nama)
                <div style="font-size: 7.5pt;">{{ $penugasan->penyetujui_jabatan ?: 'Director' }}</div>
                @endif
            </td>
            <td>
                <div class="statement-text">
                    Bersama ini kami konfirmasikan bahwa nama-nama tersebut di atas telah kami masukkan sebagai calon peserta.<br>
                    Serta melalui dokumen ini, kami tugaskan nama yang bersangkutan untuk dapat mengikuti kegiatan sesuai dengan jadwal yang telah di tentukan melalui PIC Training
                </div>
                <div style="margin-bottom: 2px;">&nbsp;</div>
                <div style="margin-bottom: 2px;">Konfirmasi,</div>
                <div class="sign-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">
                    {{ $penugasan->konfirmasi_nama ?: 'Herwin Gultom' }}
                </div>
                <div style="font-size: 7.5pt;">
                    {{ $penugasan->konfirmasi_jabatan ?: 'HRGA Deputy Div. Head' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER CODE --}}
    <table class="footer-table">
        <tr>
            <td class="footer-accent" style="width: 80%;"></td>
            <td class="footer-code">
                <span class="footer-box">{{ $penugasan->no_form ?: 'Form 013/WI-' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
