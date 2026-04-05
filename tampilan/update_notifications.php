<?php
include '../koneksi.php';

$sql = "UPDATE notifikasi SET status = 'dibaca' WHERE status = 'baru'";

if ($koneksi->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $koneksi->error]);
}

$koneksi->close();
?>
