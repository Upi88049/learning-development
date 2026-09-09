<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran & Penugasan Training Out House</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #333; line-height: 1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); padding: 24px 30px; text-align: left;">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.2px;">
                                            Dharma Learning Center (DLC)
                                        </h1>
                                        <p style="color: #e0e7ff; margin: 6px 0 0; font-size: 13px;">
                                            Pemberitahuan Formulir Pendaftaran & Penugasan Training Out House
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 28px 30px;">
                            <p style="margin-top: 0; font-size: 15px; color: #1e293b;">
                                Kepada Yth. Bapak/Ibu <strong>{{ $namaIm }}</strong>,
                            </p>
                            <p style="font-size: 14px; color: #475569; margin-bottom: 20px;">
                                Permohonan training <strong>Out House</strong> yang Anda ajukan telah diverifikasi dan disetujui oleh tim DLC. Dokumen <strong>Formulir Pendaftaran & Penugasan Training</strong> telah diterbitkan dan saat ini telah aktif di portal Immediate Manager Anda.
                            </p>

                            <!-- Alert Box -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #0d6efd; padding: 12px 16px; border-radius: 4px; margin-bottom: 22px;">
                                <p style="margin: 0; font-size: 13px; color: #1e40af; font-weight: 500;">
                                    📎 Dokumen formulir resmi berformat <strong>PDF</strong> terlampir langsung pada email ini untuk kemudahan arsip & cetak dokumen Anda.
                                </p>
                            </div>

                            <!-- Detail Ringkasan Table -->
                            <h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin: 0 0 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                                Informasi Training
                            </h3>
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 20px; font-size: 13px;">
                                <tr>
                                    <td width="35%" style="padding: 6px 0; color: #64748b; font-weight: 500;">No. Formulir</td>
                                    <td width="5%" style="padding: 6px 0; color: #64748b;">:</td>
                                    <td width="60%" style="padding: 6px 0; color: #0f172a; font-weight: 600;">{{ $penugasan->no_form ?: '-' }}</td>
                                </tr>
                                @if($penugasan->requestOuthouse && $penugasan->requestOuthouse->no_request)
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">No. Request</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">{{ $penugasan->requestOuthouse->no_request }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Judul Training</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">{{ $penugasan->nama_training }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Jenis Training</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">{{ $penugasan->jenis_training }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Sub Co / Divisi</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">{{ $penugasan->sub_co }} / {{ $penugasan->divisi }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Waktu / Tempat</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">{{ $penugasan->tempat_tanggal_training ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Jumlah Peserta</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">{{ $penugasan->jumlah_peserta }} Orang</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Biaya per Peserta</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0f172a;">Rp {{ number_format($penugasan->biaya_per_peserta, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Total Biaya</td>
                                    <td style="padding: 6px 0; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; color: #0d6efd; font-weight: 700; font-size: 14px;">Rp {{ number_format($penugasan->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            <!-- Daftar Peserta -->
                            <h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin: 20px 0 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                                Daftar Peserta Pelatihan
                            </h3>
                            <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin-bottom: 24px; font-size: 12.5px;">
                                <thead>
                                    <tr style="background-color: #f8fafc; color: #475569; text-align: left;">
                                        <th style="padding: 8px 10px; border: 1px solid #e2e8f0; text-align: center; width: 35px;">No</th>
                                        <th style="padding: 8px 10px; border: 1px solid #e2e8f0; width: 90px;">NPK</th>
                                        <th style="padding: 8px 10px; border: 1px solid #e2e8f0;">Nama Peserta</th>
                                        <th style="padding: 8px 10px; border: 1px solid #e2e8f0;">Bagian / Dept</th>
                                        <th style="padding: 8px 10px; border: 1px solid #e2e8f0;">Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penugasan->peserta as $idx => $p)
                                    <tr style="background-color: {{ $idx % 2 === 0 ? '#ffffff' : '#fbfcfd' }};">
                                        <td style="padding: 8px 10px; border: 1px solid #e2e8f0; text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                                        <td style="padding: 8px 10px; border: 1px solid #e2e8f0; color: #475569;">{{ $p['npk'] ?? '-' }}</td>
                                        <td style="padding: 8px 10px; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">{{ $p['nama'] ?? '-' }}</td>
                                        <td style="padding: 8px 10px; border: 1px solid #e2e8f0; color: #475569;">{{ $p['bagian'] ?? '-' }}</td>
                                        <td style="padding: 8px 10px; border: 1px solid #e2e8f0; color: #475569;">{{ $p['jabatan'] ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" style="padding: 12px; border: 1px solid #e2e8f0; text-align: center; color: #94a3b8;">
                                            Tidak ada data peserta.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Call to Action Button -->
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin: 28px 0 10px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $portalUrl }}" target="_blank" style="display: inline-block; background-color: #0d6efd; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 12px 28px; border-radius: 6px; box-shadow: 0 2px 6px rgba(13, 110, 253, 0.35);">
                                            Buka Portal Learning & Development
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; color: #94a3b8; text-align: center; margin-top: 14px;">
                                Anda juga dapat mengunduh formulir resmi kapan saja melalui menu riwayat permohonan di portal.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #64748b; font-weight: 500;">
                                Learning & Development Center (DLC)
                            </p>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #94a3b8;">
                                PT Dharma Polimetal Tbk &bull; Sistem Informasi Learning & Development
                            </p>
                            <p style="margin: 8px 0 0; font-size: 10px; color: #cbd5e1;">
                                Pesan ini dihasilkan secara otomatis oleh sistem. Harap tidak membalas email ini secara langsung.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
