<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Peminjaman SarPras</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 8px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #20B2AA;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            color: #20B2AA;
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 3px 0;
        }
        
        .header h2 {
            color: #666;
            font-size: 11px;
            font-weight: normal;
            margin: 0 0 5px 0;
        }
        
        .header .info {
            font-size: 8px;
            color: #888;
        }
        
        .filter-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 6px;
            margin-bottom: 8px;
            font-size: 8px;
        }
        
        .filter-info h3 {
            color: #20B2AA;
            font-size: 9px;
            margin: 0 0 4px 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 2px;
        }
        
        .filter-info p {
            margin: 2px 0;
            font-size: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 8px;
        }
        
        th {
            background: #20B2AA;
            color: white;
            padding: 4px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #20B2AA;
            font-size: 8px;
        }
        
        td {
            padding: 3px;
            border: 1px solid #dee2e6;
            vertical-align: top;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            color: white;
        }
        
        .status-dikembalikan { background: #2E8B57; }
        .status-disetujui { background: #20B2AA; }
        .status-pengembalian_diajukan { background: #ffc107; color: #333; }
        .status-ditolak { background: #dc3545; }
        .status-pengembalian-ditolak { background: #dc3545; }
        .status-menunggu { background: #6c757d; }
        
        .barang-text {
            font-size: 7px;
            line-height: 1.1;
        }
        
        .barang-item {
            margin: 1px 0;
        }
        
        /* Compact layout */
        .nama-peminjam {
            font-weight: bold;
            font-size: 8px;
        }
        
        .jurusan-info {
            font-size: 7px;
            color: #666;
        }
        
        .kode-peminjaman {
            font-size: 7px;
            color: #888;
        }
        
        .tanggal-info {
            font-size: 7px;
            line-height: 1.1;
        }
        
        .tanggal-label {
            font-weight: bold;
        }
        
        /* Responsive table untuk PDF */
        @media print {
            body {
                padding: 5px;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            thead {
                display: table-header-group;
            }
        }
        
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ARSIP PEMINJAMAN SARPRA</h1>
        <h2>Laporan Bulanan Peminjaman Barang & Ruangan</h2>
        <div class="info">
            Dicetak: {{ format_tanggal(now(), true) }} | Total: {{ $peminjamans->count() }} data peminjaman
        </div>
    </div>

    <!-- Filter Information -->
    @if(!empty($filterInfo))
    <div class="filter-info">
        <strong>Filter:</strong>
        @if(isset($filterInfo['kode_peminjaman']))
            Kode: {{ $filterInfo['kode_peminjaman'] }} |
        @endif
        @if(isset($filterInfo['nama']))
            Nama: {{ $filterInfo['nama'] }} |
        @endif
        @if(isset($filterInfo['bulan']))
            Bulan Kegiatan: {{ $filterInfo['bulan'] }} |
        @endif
        @if(isset($filterInfo['tanggal_mulai']))
            Mulai: {{ format_tanggal($filterInfo['tanggal_mulai']) }} |
        @endif
        @if(isset($filterInfo['tanggal_selesai']))
            Selesai: {{ format_tanggal($filterInfo['tanggal_selesai']) }}
        @endif
    </div>
    @endif

    <!-- Data Table -->
    @if($peminjamans->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 15%">Nama & Unit</th>
                <th style="width: 10%">HP</th>
                <th style="width: 15%">Kegiatan</th>
                <th style="width: 12%">Periode</th>
                <th style="width: 6%">Status</th>
                <th style="width: 20%">Barang</th>
                <th style="width: 19%">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="nama-peminjam">{{ $p->nama }}</div>
                    <div class="jurusan-info">{{ $p->unit }}</div>
                    <div class="kode-peminjaman">{{ $p->kode_peminjaman }}</div>
                </td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ Str::limit($p->nama_kegiatan, 20) }}</td>
                <td>
                    <div class="tanggal-info">
                        {{ format_tanggal($p->tanggal_mulai) }}<br>
                        {{ format_tanggal($p->tanggal_selesai) }}
                    </div>
                </td>
                <td>
                    <span class="status-badge status-{{ str_replace(' ', '-', $p->status) }}">
                        @if($p->status == 'pengembalian_diajukan')
                            Pengembalian
                        @elseif($p->status == 'pengembalian ditolak')
                            Ditolak
                        @else
                            {{ ucfirst($p->status) }}
                        @endif
                    </span>
                </td>
                <td>
                    <div class="barang-text">
                        @foreach($p->details as $detail)
                        <div class="barang-item">• {{ Str::limit($detail->barang->nama ?? '-', 15) }} ({{ $detail->jumlah }})</div>
                        @endforeach
                        @if($p->details->count() == 0)
                        <div class="barang-item">-</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="barang-text">
                        @foreach($p->detailsRuangan as $detail)
                        <div class="barang-item">• {{ Str::limit($detail->ruangan->nama ?? '-', 15) }}</div>
                        @endforeach
                        @if($p->detailsRuangan->count() == 0)
                        <div class="barang-item">-</div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        Tidak ada data peminjaman yang sesuai dengan filter yang dipilih.
    </div>
    @endif

    <!-- Summary Statistics -->
    @if($peminjamans->count() > 0)
    <div class="filter-info" style="margin-top: 10px;">
        <h3>Ringkasan Statistik</h3>
        <p><strong>Total Peminjaman:</strong> {{ $peminjamans->count() }} data</p>
        <p><strong>Total Barang Dipinjam:</strong> {{ $peminjamans->sum(function($p) { return $p->details->sum('jumlah'); }) }} unit</p>
        <p><strong>Total Ruangan Dipinjam:</strong> {{ $peminjamans->sum(function($p) { return $p->detailsRuangan->count(); }) }} ruangan</p>
        <p><strong>Status:</strong> 
            Menunggu: {{ $peminjamans->where('status', 'menunggu')->count() }} | 
            Disetujui: {{ $peminjamans->where('status', 'disetujui')->count() }} | 
            Ditolak: {{ $peminjamans->where('status', 'ditolak')->count() }} | 
            Dikembalikan: {{ $peminjamans->where('status', 'dikembalikan')->count() }}
        </p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Dokumen otomatis SarPras © {{ date('Y') }} - Laporan Arsip Peminjaman Bulanan
    </div>
</body>
</html> 