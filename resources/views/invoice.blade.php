<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice SPP - {{ $transaction->student->name ?? 'Siswa' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .invoice-header {
            background: #435ebe;
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .invoice-header img {
            height: 70px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .invoice-body {
            padding: 30px;
        }
        .invoice-info {
            margin-bottom: 25px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .invoice-table th {
            background: #f0f7ff;
            color: #435ebe;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .status-lunas {
            background: #d4edda;
            color: #155724;
        }
        .status-belum {
            background: #f8d7da;
            color: #721c24;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function downloadPDF() {
        var element = document.querySelector(".invoice-box"); // elemen invoice
        html2pdf(element, {
            margin: 10,
            filename: 'invoice.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
        });
    }
    </script>

    <button class="btn btn-danger" onclick="downloadPDF()">
    🖨️ Download PDF
    </button>

    <div class="invoice-box">
    <!-- Header -->
    <div class="invoice-header">
        <img src="{{ asset('img/logo-SMK-sandikta-PNG.png') }}" alt="Logo Sekolah">
        <h2>SMK SANDIKTA</h2>
        <p> Jalan Raya Hankam No.208, RT.006/RW.008, </br>
            Kelurahan Jatirahayu, Kecamatan Pondok Melati,</br>
            Kota Bekasi, Jawa Barat 17414 </br>
        </p>
    </div>

    <!-- Body -->
    <div class="invoice-body">
        <div class="invoice-info">
            <h4>Invoice SPP</h4>
            <p><strong>Nama Siswa:</strong> {{ $transaction->student->name ?? 'Siswa' }}</p>
            <p><strong>Kelas:</strong> {{ $transaction->student->schoolClass->name ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y') }}</p>
            <p><strong>Status:<span class="status-badge status-lunas">Lunas</span></strong></p>
        </div>

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Semester</th> 
                    <th class="text-end">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran SPP</td>
                    <td>
                        @php
                            // Ambil angka bulan (1-12) dari tanggal transaksi
                            $bulan = $transaction->created_at->format('n'); 
                            
                            // Logika: Juli (7) s/d Desember (12) = Ganjil
                            // Sisanya (Januari - Juni) = Genap
                            if ($bulan >= 7) {
                                $ketSemester = "Ganjil (Juli - Desember)";
                            } else {
                                $ketSemester = "Genap (Januari - Juni)";
                            }
                        @endphp
                        
                        {{ $ketSemester }}
                    </td>
                    <td class="text-end">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total -->
        <div class="text-end mt-4">
            <h5>Total: Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h5>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas pembayaran Anda 🙏</p>
        <p>Invoice ini dicetak otomatis oleh sistem.</p>
    </div>
</div>
</body>
</html>
