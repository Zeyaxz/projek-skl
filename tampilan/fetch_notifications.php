<?php
include '../koneksi.php';

header('Content-Type: application/json');

if (isset($_POST['NIM'])) {
    $NIM = $_POST['NIM'];

    // Gunakan prepared statement untuk keamanan
    $sql = "SELECT Id_Sertifikat AS id, Sertifikat AS file, pesan, notifikasi.status AS status FROM notifikasi INNER JOIN sertifikat USING(Id_Sertifikat) WHERE sertifikat.NIM = ? ORDER BY created_at DESC, Id_Sertifikat DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $NIM);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifikasi = [];
    $belumDibaca = 0;
    $id = null;
    $file = null;
    $pesan = null;
    $status = null;

    while ($row = $result->fetch_assoc()) {
        $notifikasi[] = $row;
        if ($row['status'] == 'baru') {
            $belumDibaca++;
        }
    }

    echo json_encode(["notifikasi" => $notifikasi, "belumDibaca" => $belumDibaca ]);

    $stmt->close();
}

?>
