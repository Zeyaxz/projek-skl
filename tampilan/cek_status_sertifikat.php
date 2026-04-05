<?php
include '../koneksi.php'; // Sesuaikan dengan file koneksi kamu

if (isset($_POST['NIM']) && isset($_POST['status'])) {
    $NIM = $_POST['NIM'];
    $status = $_POST['status'];

    // Jika status "all", cari semua sertifikat mahasiswa
    if ($status == "all") {
        $query = "SELECT COUNT(*) AS jumlah FROM sertifikat WHERE NIM = '$NIM'";
    } else {
        $query = "SELECT COUNT(*) AS jumlah FROM sertifikat WHERE NIM = '$NIM' AND status = '$status'";
    }

    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data['jumlah'] > 0) {
        echo "ok";
    } else {
        echo "no";
    }

}
?>