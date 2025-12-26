<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Refund #{{ $refund->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-item {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .info-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .info-value {
            font-size: 13px;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-menunggu_approval { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-processed { background: #dbeafe; color: #1e40af; }
        .amount {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1e40af; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cetak Dokumen
        </button>
        <a href="{{ route('refund.show', $refund) }}" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; margin-left: 10px;">
            Kembali
        </a>
    </div>

    <div class="container">
        <div class="header">
            <h1>BUKTI REFUND</h1>
            <p>Formulir Pengembalian Dana</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nomor Refund</div>
                <div class="info-value">REF-{{ str_pad($refund->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Refund</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d F Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Jenis Refund</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $refund->status }}">
                        @if($refund->status === 'draft') Draft
                        @elseif($refund->status === 'menunggu_approval') Menunggu Approval
                        @elseif($refund->status === 'approved') Disetujui
                        @elseif($refund->status === 'rejected') Ditolak
                        @elseif($refund->status === 'processed') Diproses
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Referensi Terkait</div>
            @if($refund->pencairanDana)
                <div class="info-item">
                    <div class="info-label">Pencairan Dana</div>
                    <div class="info-value">
                        Nomor: {{ $refund->pencairanDana->nomor_pencauran ?? $refund->pencairanDana->id }}<br>
                        Jumlah: {{ formatRupiah($refund->pencairanDana->jumlah_pencairan) }}
                    </div>
                </div>
            @elseif($refund->pengajuanDana)
                <div class="info-item">
                    <div class="info-label">Pengajuan Dana</div>
                    <div class="info-value">
                        Nomor: {{ $refund->pengajuanDana->nomor_pengajuan }}<br>
                        Uraian: {{ $refund->pengajuanDana->uraian }}
                    </div>
                </div>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Detail Pengembalian</div>
            <div style="margin-bottom: 15px;">
                <div class="info-label">Alasan Refund</div>
                <div class="info-value">{{ $refund->alasan_refund }}</div>
            </div>
            <div style="margin-bottom: 15px;">
                <div class="info-label">Rekening Tujuan</div>
                <div class="info-value">{{ $refund->rekening_tujuan ?? '-' }}</div>
            </div>
            <div>
                <div class="info-label">Jumlah Pengembalian</div>
                <div class="amount">{{ formatRupiah($refund->jumlah_refund) }}</div>
            </div>
        </div>

        @if($refund->catatan_approval)
            <div class="section">
                <div class="section-title">Catatan Approval</div>
                <p>{{ $refund->catatan_approval }}</p>
                <p style="margin-top: 10px; font-size: 11px; color: #666;">
                    Oleh: {{ $refund->approvedBy->name ?? '-' }}
                </p>
            </div>
        @endif

        @if($refund->status === 'processed' && $refund->tanggal_transfer)
            <div class="section">
                <div class="section-title">Informasi Proses</div>
                <div class="info-item">
                    <div class="info-label">Tanggal Transfer</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($refund->tanggal_transfer)->format('d F Y') }}</div>
                </div>
            </div>
        @endif

        <div class="footer">
            <div class="signature">
                <p>Diajukan oleh,</p>
                <div class="signature-space"></div>
                <p><strong>{{ $refund->createdBy->name ?? '-' }}</strong></p>
                <p style="font-size: 10px; color: #666;">{{ \Carbon\Carbon::parse($refund->created_at)->format('d F Y') }}</p>
            </div>
            @if($refund->approvedBy)
                <div class="signature">
                    <p>Disetujui oleh,</p>
                    <div class="signature-space"></div>
                    <p><strong>{{ $refund->approvedBy->name ?? '-' }}</strong></p>
                    <p style="font-size: 10px; color: #666;">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d F Y') }}</p>
                </div>
            @endif
        </div>

        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #999;">
            <p>Dokumen ini dicetak secara otomatis dari sistem e-Budget pada {{ now()->format('d F Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
