<?php
require_once "../auth.php";

$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

if (isset($userData['Username'])) {
    $username = $userData['Username'];
    $nama_lengkap = $userData['Nama_Lengkap'];
    $role = "operator";
} elseif (isset($userData['NIM'])) {
    $NIM = $userData['NIM'];
    $nama_lengkap = $userData['Nama_mahasiswa'];
    $role = "mahasiswa";
}

// Mengambil parameter dari URL
$pdfFile = isset($_GET['file']) ? $_GET['file'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$NIM = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT NIM FROM `sertifikat` WHERE Id_Sertifikat = $id"));
$NIM = $NIM['NIM'];
// Validasi input
if (!$pdfFile) {
    die("File PDF tidak ditemukan! Tambahkan parameter ?file=namaFile.pdf di URL.");
}

// Ambil data mahasiswa dan sertifikat
$tgl = date("Y-m-d");
$query = "
    SELECT Nama_mahasiswa, NIM, Prodi, Kelas, No_Telp, Email, Angkatan, Kategori, Sub_Kategori, Jenis_Kegiatan, Status, Catatan, Tanggal_Upload
    FROM sertifikat
    INNER JOIN kegiatan USING(Id_Kegiatan)
    INNER JOIN kategori USING(Id_Kategori)
    INNER JOIN mahasiswa USING(NIM)
    INNER JOIN Prodi USING(Id_Prodi)
    WHERE Id_Sertifikat = '$id'
";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
$tanggal_upload = $data['Tanggal_Upload'];
// Proses update status sertifikat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    if ($status === "delete") {
        $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM sertifikat WHERE Id_Sertifikat='$id'"));
        if ($data > 0) {
            $file_path = "../sertifikat/" . $data['Sertifikat'];//mengambil file pdfnya
            if (file_exists($file_path)) {//cek data
                $sql = mysqli_query($koneksi, "DELETE FROM sertifikat WHERE Id_Sertifikat='$id'");
                if ($sql) {
                    unlink($file_path);//hapus file pdfnya
                    notifHapus("sertifikat", "../tampilan/halaman_utama.php?page=sertifikat_mahasiswa");
                } else {
                    notifGagalHapus("sertifikat", "../tampilan/halaman_utama.php?page=sertifikat_mahasiswa");
                }
            } else {
                notifGagalHapus("sertifikat", "../tampilan/halaman_utama.php?page=sertifikat_mahasiswa");
            }
        } else {
            notifGagalHapus("sertifikat", "../tampilan/halaman_utama.php?page=sertifikat_mahasiswa");
        }
        exit();
    }
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan']) : NULL;

    $updateQuery = "
        UPDATE sertifikat SET
        Status = '$status',
        Catatan = " . ($status == "Tidak Valid" ? "'$catatan'" : "NULL") . ",
        Tanggal_Status_Berubah = '$tgl'
        WHERE Id_Sertifikat = '$id'
    ";
    $notifikasi = "INSERT INTO `notifikasi` (`Id_Sertifikat`, `pesan`, `status`) VALUES 
        ('$id', 'Status sertifikat yang anda upload pada tanggal $tanggal_upload telah berubah menjadi $status.', 'baru')";

    $hasil = mysqli_query($koneksi, $updateQuery);
    $hasil = mysqli_query($koneksi, $notifikasi);

    if ($hasil) {
        notifUbah("sertifikat", "halaman_utama.php?page=cek_sertifikat&id=$id&file=$pdfFile");
    } else {
        notifGagalUbah("sertifikat", "halaman_utama.php?page=sertifikat_operator");
    }
}
?>

<script>
    function toggleInvalid() {
        document.getElementById('btn-tidak-valid').style.display = 'none';
        document.getElementById('btn-valid').style.display = 'none';
        document.getElementById('btn-batal').style.display = 'inline-block';
        document.getElementById('btn-submit').style.display = 'inline-block';
        document.getElementById('catatan-container').style.display = 'block';
        document.getElementById('floatingTextarea').focus();
    }

    function cancelInvalid() {
        document.getElementById('btn-tidak-valid').style.display = 'inline-block';
        document.getElementById('btn-valid').style.display = 'inline-block';
        document.getElementById('btn-batal').style.display = 'none';
        document.getElementById('btn-submit').style.display = 'none';
        document.getElementById('catatan-container').style.display = 'none';
    }
</script>

<style>
    body {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .container-fluid {
        display: flex;
        height: 100%;
    }

    .pdf-container {
        max-height: calc(100vh - 70px);
        flex: 6;
        border: 2px solid #ddd;
    }

    .pdf-container embed {
        width: 100%;
        height: calc(100vh - 70px);
        object-fit: contain;
    }

    .mahasiswa-container {
        flex: 2;
        padding: 20px;
        background-color: #FFF;
        overflow: scroll;
    }
</style>

<div class="container-fluid">
    <div class="pdf-container">
        <embed src="../sertifikat/<?= $pdfFile ?>" type="application/pdf">
    </div>

    <div class="mahasiswa-container">
        <?php if ($data["Status"] == "Menunggu Validasi"): ?>
            <button class='btn btn-warning mb-3' type='button' disabled>
                <span class='spinner-grow spinner-grow-sm' aria-hidden='true'></span>
                <span role='status'>&nbsp; Menunggu Validasi...</span>
            </button>
        <?php elseif ($data["Status"] == "Tidak Valid"): ?>
            <button class='btn btn-danger mb-3' type='button' disabled>
                <span class='btn-close text-light' aria-label='Close'></span>
                <span role='status'>&nbsp;&nbsp; Tidak Valid</span>
            </button>
        <?php endif; ?>

        <h4 class="mb-3">Detail mahasiswa</h4>
        <ul class="list-group">
            <li class="list-group-item"><strong>Nama:</strong> <?= $data["Nama_mahasiswa"] ?></li>
            <li class="list-group-item"><strong>NIM:</strong> <?= $data["NIM"] ?></li>
            <li class="list-group-item"><strong>Kelas:</strong> <?= $data["Prodi"] . " " . $data["Kelas"] ?></li>
            <li class="list-group-item"><strong>Telepon:</strong> <?= $data["No_Telp"] ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= $data["Email"] ?></li>
            <li class="list-group-item"><strong>Angkatan:</strong> <?= $data["Angkatan"] ?></li>
        </ul>

        <h4 class="mb-3 mt-3">Kategori Kegiatan</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Kategori:</strong> <?= $data["Kategori"] ?></li>
            <li class="list-group-item"><strong>Sub Kategori:</strong> <?= $data["Sub_Kategori"] ?></li>
            <li class="list-group-item"><strong>Kegiatan:</strong> <?= $data["Jenis_Kegiatan"] ?></li>
        </ul>
        <?php if ($data["Status"] == "Menunggu Validasi" && $role === "operator") { ?>
            <div class="d-flex justify-content-between mb-3">
                <button id="btn-tidak-valid" type="button" class="btn btn-danger btn-lg" onclick="toggleInvalid()">Tidak
                    Valid</button>
                <button id="btn-batal" type="button" class="btn btn-dark btn-lg" style="display: none;"
                    onclick="cancelInvalid()">Batal</button>
                <form action="" method="POST">
                    <input type="hidden" name="status" value="Valid">
                    <button type="submit" id="btn-valid" class="btn btn-success btn-lg">Valid</button>
                </form>
            </div>

            <form action="" method="POST">
                <div id="catatan-container" class="form-floating" style="display: none;">
                    <textarea name="catatan" class="form-control" placeholder="Tulis catatan di sini..."
                        id="floatingTextarea2" style="height: 150px"></textarea>
                    <label for="floatingTextarea2">Catatan</label>
                </div>
                <input type="hidden" name="status" value="Tidak Valid">
                <button type="submit" id="btn-submit" class="btn btn-success btn-lg mt-3 w-100 mb-5"
                    style="display: none;">Submit</button>
            </form>

        <?php } elseif ($data["Status"] == "Tidak Valid") { ?>
            <div id="catatan-container" class="form-floating">
                <textarea readonly name="catatan" class="form-control" placeholder="Tulis catatan di sini..."
                    id="floatingTextarea2" style="height: 150px"><?= $data["Catatan"] ?></textarea>
                <label for="floatingTextarea2">Catatan</label>
            </div>
            <?php if ($role === "mahasiswa") { ?>
                <form action="" method="post">
                    <input type="hidden" name="status" value="delete">
                    <button type="submit" id="btn-submit" class="btn btn-danger btn-lg mt-3 w-100 mb-5">Delete</button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
</div>