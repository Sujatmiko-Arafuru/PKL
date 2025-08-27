<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Peminjaman SarPras</title>
    <style>
        /* Import PDF optimization CSS */
        @import url('{{ asset('assets/css/pdf-optimization.css') }}');
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #20B2AA;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #20B2AA;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 10px 0;
        }
        
        .header .info {
            font-size: 11px;
            color: #888;
        }
        
        .filter-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .filter-info h3 {
            color: #20B2AA;
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .filter-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th {
            background: #20B2AA;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #20B2AA;
        }
        
        td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        
        .status-dikembalikan { background: #2E8B57; }
        .status-disetujui { background: #20B2AA; }
        .status-pengembalian_diajukan { background: #ffc107; color: #333; }
        .status-ditolak { background: #dc3545; }
        .status-pengembalian-ditolak { background: #dc3545; }
        .status-menunggu { background: #6c757d; }
        
        .barang-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .barang-list li {
            margin: 2px 0;
            padding: 2px 0;
        }
        
        /* Styling untuk kolom barang yang dipinjam */
        td:last-child {
            word-wrap: break-word;
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            line-height: 1.2;
            font-size: 9px;
        }
        
        /* Responsive table untuk PDF */
        @media print {
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ARSIP PEMINJAMAN SARANA DAN PRASARANA</h1>
        <h2>Sistem Peminjaman Sarana dan Prasarana (SarPras)</h2>
        <div class="info">
            <p>Dicetak pada: {{ format_tanggal(now(), true) }}</p>
            <p>Total Data: {{ $peminjamans->count() }} peminjaman</p>
        </div>
    </div>

    <!-- Filter Information -->
    @if(!empty($filterInfo))
    <div class="filter-info">
        <h3>📋 Informasi Filter</h3>
        @if(isset($filterInfo['search']))
            <p><strong>Pencarian:</strong> {{ $filterInfo['search'] }}</p>
        @endif
        @if(isset($filterInfo['status']))
            <p><strong>Status:</strong> {{ ucfirst($filterInfo['status']) }}</p>
        @endif
        @if(isset($filterInfo['tanggal_mulai']))
            <p><strong>Tanggal Mulai:</strong> {{ format_tanggal($filterInfo['tanggal_mulai']) }}</p>
        @endif
        @if(isset($filterInfo['tanggal_selesai']))
            <p><strong>Tanggal Selesai:</strong> {{ format_tanggal($filterInfo['tanggal_selesai']) }}</p>
        @endif
    </div>
    @endif

    <!-- Data Table -->
    @if($peminjamans->count() > 0)
    <table class="pdf-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Peminjam</th>
                <th class="col-hp">No HP</th>
                <th class="col-jurusan">Jurusan/Ormawa</th>
                <th class="col-kegiatan">Nama Kegiatan</th>
                <th class="col-tanggal">Tanggal Pinjam</th>
                <th class="col-status">Status</th>
                <th class="col-barang">Barang Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $index => $p)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-nama">
                    <div class="nama-peminjam">{{ $p->nama }}</div>
                    <div class="kode-peminjaman">Kode: {{ $p->kode_peminjaman }}</div>
                </td>
                <td class="col-hp">{{ $p->no_telp }}</td>
                <td class="col-jurusan">{{ $p->unit }}</td>
                <td class="col-kegiatan">{{ Str::limit($p->nama_kegiatan, 25) }}</td>
                <td class="col-tanggal">
                    <div class="tanggal-info">
                        <span class="tanggal-label">Mulai:</span> {{ format_tanggal($p->tanggal_mulai) }}<br>
                        <span class="tanggal-label">Selesai:</span> {{ format_tanggal($p->tanggal_selesai) }}
                    </div>
                </td>
                <td class="col-status">
                    <span class="status-badge status-{{ str_replace(' ', '-', $p->status) }}">
                        @if($p->status == 'pengembalian_diajukan')
                            Pengembalian Diajukan
                        @elseif($p->status == 'pengembalian ditolak')
                            Pengembalian Ditolak
                        @else
                            {{ ucfirst($p->status) }}
                        @endif
                    </span>
                </td>
                <td class="col-barang">
                    <div class="barang-text">
                        @php
                            $barangList = [];
                            foreach($p->details as $detail) {
                                $barangList[] = '<span class="barang-item">' . ($detail->barang->nama ?? '-') . ' (' . $detail->jumlah . ')</span>';
                            }
                            echo implode(', ', $barangList);
                        @endphp
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h3>📭 Tidak Ada Data</h3>
        <p>Tidak ada data peminjaman yang sesuai dengan filter yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Dokumen ini dibuat secara otomatis oleh sistem SarPras</strong></p>
        <p>© {{ date('Y') }} Sistem Peminjaman Sarana dan Prasarana. All rights reserved.</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html> 