<?php
include '../koneksi.php'; // Sesuaikan dengan file koneksi database

if (isset($_POST['angkatan'])) {
    $angkatan = $_POST['angkatan'];

    $query = mysqli_query($koneksi, "SELECT Nama_mahasiswa, NIM FROM mahasiswa WHERE Angkatan = '$angkatan'");

    while ($data = mysqli_fetch_assoc($query)) {
        echo "<option value='{$data['NIM']}'>{$data['Nama_mahasiswa']}</option>";
    }
}
?>
