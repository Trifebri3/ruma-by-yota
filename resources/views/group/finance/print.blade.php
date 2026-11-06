<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas Kelompok — {{ $group->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; color: #111; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 16px; font-weight: bold; }
        .sub { font-size: 12px; color: #555; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .box { border: 1px solid #ccc; padding: 10px; border-radius: 4px; width: 30%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .masuk { color: green; font-weight: bold; }
        .keluar { color: red; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #B88E3E; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #eee; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <div class="title">BUKU KAS KELOMPOK — {{ strtoupper($group->name) }}</div>
        <div class="sub">Program Pendampingan Ruang Usaha Masyarakat Alam (RUMA) | SiYota.org</div>
        <div class="sub">Wilayah: {{ $group->regency }} • Tanggal Cetak: {{ date('d F Y') }}</div>
    </div>

    <div class="summary">
        <div class="box">
            <div>Total Pemasukan</div>
            <div style="font-size: 14px; font-weight: bold; color: green;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="box">
            <div>Total Pengeluaran</div>
            <div style="font-size: 14px; font-weight: bold; color: red;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
        <div class="box">
            <div>Sisa Saldo Kas</div>
            <div style="font-size: 14px; font-weight: bold;">Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Keterangan / Transaksi</th>
                <th>Kategori</th>
                <th class="text-right" style="width: 100px;">Masuk (Rp)</th>
                <th class="text-right" style="width: 100px;">Keluar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allRecords as $i => $rec)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($rec->transaction_date)->format('d/m/Y') }}</td>
                <td>{{ $rec->description }}</td>
                <td>{{ $rec->category }}</td>
                <td class="text-right masuk">{{ $rec->type === 'masuk' ? number_format($rec->amount, 0, ',', '.') : '-' }}</td>
                <td class="text-right keluar">{{ $rec->type === 'keluar' ? number_format($rec->amount, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #888;">Belum ada catatan transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; text-align: center;">
        <div style="width: 200px;">
            Mengetahui,<br>
            Ketua Kelompok
            <br><br><br><br>
            <b>( {{ $group->leader_name }} )</b>
        </div>
        <div style="width: 200px;">
            Tasikmalaya, {{ date('d F Y') }}<br>
            Pengelola Keuangan
            <br><br><br><br>
            <b>( Ibu Rina )</b>
        </div>
    </div>
</body>
</html>

