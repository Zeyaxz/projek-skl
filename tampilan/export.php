<?php
require_once "../auth.php";
// error_reporting(0);

$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

if (isset($userData['Username'])) {
    $username = $userData['Username'];
    $nama_lengkap = $userData['Nama_Lengkap'];
    $role = "operator";
}

require('../fpdf186/fpdf.php');

if (isset($_GET['export'])) {
    if (($_GET['export']) == 'Kategori') {
        if (isset($_POST['Sub_Kategori'])) {
            $kategori = $_POST['Sub_Kategori'];

            // Buat objek PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetMargins(15, 10, 15);
            $pdf->SetFont('Arial', 'B', 14);

            // ukuran A4 (210 mm x 297 mm)

            // Hitung posisi tengah tabel
            $lebar_tabel = 190; // Total lebar tabel dikurangi agar muat di halaman A4
            $margin_kiri = 15; // Sesuai dengan yang ditetapkan di SetMargins()
            $lebar_halaman = $pdf->GetPageWidth() - (2 * $margin_kiri); // Lebar halaman setelah dikurangi margin
            $posisi_tengah = $margin_kiri + (($lebar_halaman - $lebar_tabel) / 2); // Hitung X yang benar


            // Judul
            $pdf->Cell(190, 6, 'Laporan Kegiatan', 0, 1, 'C');
            $pdf->Ln(5);

            $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Sub_Kategori FROM kategori WHERE Sub_Kategori = '$kategori'"));
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(20, 6, 'Kategori', 0, 0, 'L');
            $pdf->Cell(35, 6, ': ' . $data['Sub_Kategori'], 0, 1, 'L');
            $pdf->Ln(2);

            // Header tabel
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(173, 216, 230); // Warna biru muda
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(20, 6, 'No', 1, 0, 'C', true);
            $pdf->Cell(140, 6, 'Kegiatan', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'Angka Kredit', 1, 1, 'C', true);

            // Ambil data dari database
            $pdf->SetFont('Arial', '', 12);
            $no = 1;
            $data_query = mysqli_query($koneksi, "SELECT Jenis_Kegiatan, Angka_Kredit FROM `kegiatan` INNER JOIN kategori USING(Id_Kategori) WHERE Sub_Kategori = '$kategori'");

            while ($data = mysqli_fetch_assoc($data_query)) {
                // Simpan posisi awal
                $x = $pdf->GetX();
                $y = $pdf->GetY();
            
                // --- Hitung tinggi yang dibutuhkan untuk MultiCell Jenis_Kegiatan
                $pdf->SetXY($posisi_tengah + 20, $y);
                $pdf->MultiCell(140, 6, $data['Jenis_Kegiatan'], 0, 'L');
                $height = $pdf->GetY() - $y;
            
                // --- Kembali ke posisi awal sebelum MultiCell untuk isi semua cell (biar sejajar)
                $pdf->SetXY($posisi_tengah, $y);
                $pdf->Cell(20, $height, $no++, 1, 0, 'C'); // No
                $pdf->SetXY($posisi_tengah + 20, $y);
                $pdf->MultiCell(140, 6, $data['Jenis_Kegiatan'], 1, 'L'); // Jenis Kegiatan
                $pdf->SetXY($posisi_tengah + 160, $y);
                $pdf->Cell(30, $height, $data['Angka_Kredit'], 1, 1, 'C'); // Angka Kredit
            }
            
            
            $pdf->Ln(5);

            // Output PDF ke browser
            $pdf->Output('I', 'Laporan_Jumlah_Kategori.pdf'); // 'D' = download otomatis
        }
        include '../404.php';
        exit();
    }

    if (($_GET['export']) == 'Sertifikat') {
        if (isset($_POST['NIM']) && isset($_POST['status'])) {
            $NIM = $_POST['NIM'];
            $status = $_POST['status'];

            // Buat objek PDF
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetMargins(15, 10, 15);
            $pdf->SetFont('Arial', 'B', 14);

            // Ukuran A4 (210 mm x 297 mm)

            //tabel tengah data
            $lebar_tabel = 170; // Total lebar tabel dikurangi agar muat di halaman A4
            $margin_kiri = 15;
            $lebar_halaman = $pdf->GetPageWidth() - (2 * $margin_kiri);
            $posisi_tengah = $margin_kiri + (($lebar_halaman - $lebar_tabel) / 2);

            // Header image
            $pdf->Image('../img/header_TI.jpg', 10, 10, 190, );
            $pdf->SetY(60);
            $pdf->Ln(5);

            // Judul
            $pdf->Cell(190, 6, 'Laporan Sertifikat', 0, 1, 'C');
            $pdf->Ln(5);

            $data1 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT NIM, Nama_mahasiswa, Angkatan, Kelas, Prodi FROM sertifikat INNER JOIN mahasiswa USING(NIM) INNER JOIN Prodi USING(Id_Prodi) WHERE NIM = '$NIM'"));
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(35, 6, 'NIM', 0, 0, 'L');
            $pdf->Cell(35, 6, ': ' . $data1['NIM'], 0, 1, 'L');
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(35, 6, 'Kelas', 0, 0, 'L');
            $pdf->Cell(35, 6, ': ' . $data1['Prodi'] . " " . $data1['Kelas'], 0, 1, 'L');
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(35, 6, 'Nama Lengkap', 0, 0, 'L');
            $pdf->Cell(100, 6, ': ' . $data1['Nama_mahasiswa'], 0, 1, 'L');
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(35, 6, 'Angkatan', 0, 0, 'L');
            $pdf->Cell(35, 6, ': ' . $data1['Angkatan'], 0, 1, 'L');
            $pdf->Ln(4);



            // Header tabel
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(173, 216, 230); // Warna biru muda
            $pdf->SetX($posisi_tengah);
            $pdf->Cell(10, 6, 'No', 1, 0, 'C', true);
            $pdf->Cell(45, 6, 'Tanggal Upload', 1, 0, 'C', true);
            $pdf->Cell(35, 6, 'Status', 1, 0, 'C', true);
            $pdf->Cell(80, 6, 'Jenis Kegiatan', 1, 1, 'C', true);

            // Ambil data dari database
            $pdf->SetFont('Arial', '', 10);
            $no = 1;
            $query = "SELECT Tanggal_Upload, Status, Jenis_Kegiatan FROM sertifikat INNER JOIN mahasiswa USING(NIM) INNER JOIN kegiatan USING(Id_Kegiatan) WHERE NIM = '$NIM'";
            if ($status != "all") {
                $query .= " AND Status = '$status'";
            }
            $data_query = mysqli_query($koneksi, $query);

            while ($data = mysqli_fetch_assoc($data_query)) {
                $pdf->SetX($posisi_tengah);
                $pdf->Cell(10, 6, $no++, 1, 0, 'C');
                $pdf->Cell(45, 6, $data['Tanggal_Upload'], 1, 0, 'C');
                $pdf->Cell(35, 6, $data['Status'], 1, 0, 'C');
                $pdf->Cell(80, 6, $data['Jenis_Kegiatan'], 1, 1, 'L');
            }
            $pdf->Ln(10);

            //judul tabel Rekap
            if ($status == "all") {
                $pdf->SetX($posisi_tengah);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetFillColor(173, 216, 230); // Warna biru muda
                $pdf->Cell(90, 6, 'Rekapitulasi Sertifikat', 1, 1, 'C', true);
            }

            // Header tabel Rekap
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX($posisi_tengah);
            $pdf->SetFillColor(173, 216, 230); // Warna biru muda
            $pdf->Cell(55, 6, 'Status', 1, 0, 'C', true);
            $pdf->Cell(35, 6, 'Total', 1, 1, 'C', true);

            // Ambil data dari database
            $pdf->SetFont('Arial', $status !== "all" ? 'B' : '', 10);
            $query_rekap = "SELECT Status, COUNT(Status) AS Total FROM sertifikat WHERE NIM = '$NIM'";
            if ($status != "all") {
                $query_rekap .= " AND Status = '$status'";
            }
            $query_rekap .= " GROUP BY Status";
            $data_query = mysqli_query($koneksi, $query_rekap);

            while ($data = mysqli_fetch_assoc($data_query)) {
                $pdf->SetX($posisi_tengah);
                $pdf->Cell(55, 6, $data['Status'], 1, 0, 'L');
                $pdf->Cell(35, 6, $data['Total'], 1, 1, 'C');
            }
            if ($status == "all") {
                $pdf->SetFont('Arial', 'B', 12); // Bold untuk total
                $pdf->SetFillColor(255, 215, 0); // Warna emas
                $pdf->SetX($posisi_tengah);
                $pdf->Cell(55, 6, 'Total Keseluruhan', 1, 0, 'L', true);
                $Total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS Total_Keseluruhan FROM sertifikat WHERE NIM = $NIM"));
                $pdf->Cell(35, 6, $Total['Total_Keseluruhan'], 1, 1, 'C', true);
            }

            // Output PDF ke browser
            $pdf->Output('I', 'Laporan_Sertifikat.pdf'); // 'D' = download otomatis
        }
        include '../404.php';
        exit();
    }

    if (($_GET['export']) == 'Poin') {
        if (isset($_COOKIE['NIM'])) {
            $NIM = $_COOKIE['NIM'];
            $poin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(Angka_Kredit) AS Point FROM `sertifikat` INNER JOIN kegiatan USING(Id_Kegiatan) WHERE NIM = $NIM AND Status='Valid';"));
            $nama = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Nama_mahasiswa FROM `mahasiswa` WHERE NIM = $NIM"));
            if ($poin['Point'] >= 30) {
                // Buat objek PDF
                $pdf = new FPDF('L', 'mm', 'A4');
                $pdf->AddPage();
                $pdf->SetMargins(15, 10, 15);
                $pdf->SetFont('Arial', 'B', 14);

                // ukuran A4 (210 mm x 297 mm)

                // header image
                $pdf->Image('../img/background-sertif.png', 0, 0, 297, 210);

                // Nama
                $pdf->SetFont('Arial', 'B', 36);
                $pdf->SetY(100);
                $pdf->Cell(277, 6, $nama['Nama_mahasiswa'], 0, 0, 'C');

                // Output PDF ke browser
                $pdf->Output('I', 'Sertifikat_SK.pdf'); // 'D' = download otomatis

                exit();
            } else {
                echo "Maaf, Anda belum mencapai 30 poin.";
                echo "Hitung mundur 10 detik, Anda akan keluar dari halaman ini...<br>"; ?>
                <script>
                    var time = 10;
                    var countdown = setInterval(function () {
                        document.body.innerHTML = "Maaf, Anda belum mencapai 30 poin.<br>Hitung mundur " + time + " detik, Anda akan keluar dari halaman ini...";
                        time--;

                        if (time < 0) {
                            clearInterval(countdown);
                            window.location.href = "../logout.php"; // Ganti dengan halaman tujuan
                        }
                    }, 1000);
                </script>
                <?php
            }
        }
        include '../404.php';
        exit();
    }
}


?>