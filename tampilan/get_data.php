<?php
require_once "../koneksi.php";

if (isset($_COOKIE['NIM'])) {
    $data_kondisi = "NIM";
    $data_pie = $_COOKIE['NIM'];
    $NIM = "WHERE $data_kondisi = '$data_pie'";
} else {
    $NIM = "";
}

$query = mysqli_query($koneksi, "SELECT 
        SUM(CASE WHEN Status = 'Valid' THEN 1 ELSE 0 END) AS valid,
        SUM(CASE WHEN Status = 'Menunggu Validasi' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN Status = 'Tidak Valid' THEN 1 ELSE 0 END) AS tidak_valid
    FROM sertifikat " . (!empty($NIM) ? "$NIM" : ""));

$data = mysqli_fetch_assoc($query);
echo json_encode($data);
?>