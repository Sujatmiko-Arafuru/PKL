<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - {{ $peminjaman->kode_peminjaman }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #20B2AA;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #20B2AA;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .header .info {
            font-size: 10px;
            color: #666;
        }
        
        .content-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            background: #20B2AA;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 0;
        }
        
        .section-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 15px;
            background: white;
        }
        
        .data-peminjam {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-start;
        }
        
        .data-item {
            flex: 1;
            min-width: 200px;
            margin-bottom: 8px;
        }
        
        .data-label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .data-value {
            font-size: 12px;
            color: #333;
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        
        .status-menunggu { background: #6c757d; }
        .status-disetujui { background: #20B2AA; }
        .status-ditolak { background: #dc3545; }
        .status-pengembalian_diajukan { background: #ffc107; color: #333; }
        .status-dikembalikan { background: #2E8B57; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }
        
        th {
            background: #20B2AA;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #20B2AA;
            font-size: 11px;
        }
        
        td {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        
        .badge-primary { background: #20B2AA; }
        .badge-success { background: #2E8B57; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-danger { background: #dc3545; }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
            font-size: 11px;
        }
        
        @media print {
            .content-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>DETAIL PEMINJAMAN SARPRA</h1>
        <div class="info">
            Dicetak: {{ format_tanggal(now(), true) }} | Kode: {{ $peminjaman->kode_peminjaman }}
        </div>
    </div>

    <!-- Data Peminjam -->
    <div class="content-section">
        <div class="section-title">Data Peminjam</div>
        <div class="section-content">
            <div class="data-peminjam">
                <div class="data-item">
                    <div class="data-label">Nama</div>
                    <div class="data-value">{{ $peminjaman->nama }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">NIM/NIP</div>
                    <div class="data-value">{{ $peminjaman->nim_nip ?? '-' }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">No. HP</div>
                    <div class="data-value">{{ $peminjaman->no_telp }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Unit/Jurusan</div>
                    <div class="data-value">{{ $peminjaman->unit }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Nama Kegiatan</div>
                    <div class="data-value">{{ $peminjaman->nama_kegiatan }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Periode Peminjaman</div>
                    <div class="data-value">{{ format_tanggal($peminjaman->tanggal_mulai) }} - {{ format_tanggal($peminjaman->tanggal_selesai) }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Tanggal Pengajuan</div>
                    <div class="data-value">{{ format_tanggal($peminjaman->created_at, true) }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Status</div>
                    <div class="data-value">
                        <span class="status-badge status-{{ $peminjaman->status }}">
                            @if($peminjaman->status == 'menunggu')
                                Menunggu
                            @elseif($peminjaman->status == 'disetujui')
                                Disetujui
                            @elseif($peminjaman->status == 'ditolak')
                                Ditolak
                            @elseif($peminjaman->status == 'pengembalian_diajukan')
                                Pengembalian Diajukan
                            @elseif($peminjaman->status == 'dikembalikan')
                                Dikembalikan
                            @else
                                {{ ucfirst($peminjaman->status) }}
                            @endif
                        </span>
                    </div>
                </div>
                @if($peminjaman->bukti)
                <div class="data-item">
                    <div class="data-label">Lampiran Bukti</div>
                    <div class="data-value">
                        <span class="badge badge-primary">📎 File terlampir</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Barang yang Dipinjam -->
    <div class="content-section">
        <div class="section-title">Barang Dipinjam</div>
        <div class="section-content">
            @if($peminjaman->details->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 70%">Nama Barang</th>
                            <th style="width: 22%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->barang->nama ?? '-' }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $detail->jumlah }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    Tidak ada barang yang dipinjam
                </div>
            @endif
        </div>
    </div>

    <!-- Ruangan yang Dipinjam -->
    <div class="content-section">
        <div class="section-title">Ruangan Dipinjam</div>
        <div class="section-content">
            @if($peminjaman->detailsRuangan->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 70%">Nama Ruangan</th>
                            <th style="width: 22%">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->detailsRuangan as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->ruangan->nama ?? '-' }}</td>
                            <td>{{ $detail->ruangan->lokasi ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    Tidak ada ruangan yang dipinjam
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Dokumen otomatis SarPras © {{ date('Y') }} - {{ $peminjaman->kode_peminjaman }}
    </div>
</body>
</html>