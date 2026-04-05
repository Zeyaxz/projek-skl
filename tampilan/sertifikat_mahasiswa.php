<?php

require_once "../auth.php";

$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

if (isset($userData['NIM'])) {
    $NIM = $userData['NIM'];
    $nama_lengkap = $userData['Nama_mahasiswa'];
    $role = "mahasiswa";
}

// Fungsi untuk mendapatkan sertifikat berdasarkan status dan kegiatan
function getSertifikat($status, $koneksi, $kegiatan = null)
{

    $NIM = $_COOKIE['NIM'];
    $whereClause = "WHERE Status='$status'";

    if (!empty($kegiatan)) {
        $whereClause .= " AND Jenis_Kegiatan LIKE '%" . $kegiatan . "%' ";
    }

    $query = "SELECT * FROM sertifikat
              INNER JOIN kegiatan USING(Id_Kegiatan)
              INNER JOIN kategori USING(Id_Kategori)
              INNER JOIN mahasiswa USING(NIM)
              $whereClause
              AND NIM = $NIM
              ORDER BY Sub_Kategori, Tanggal_Upload ASC";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card border-secondary mb-2">
                <div class="card-header">
                    <button class="btn btn-dark"> <?= $data['Kategori'] ?> </button>
                    <strong> <?= $data['Jenis_Kegiatan'] ?> </strong>
                </div>
                <div class="card-body">
                    <a href="halaman_utama.php?page=cek_sertifikat&id=<?= $data['Id_Sertifikat'] ?>&file=<?= $data['Sertifikat'] ?>"
                        style="text-decoration:none;">
                        <i class="bi bi-filetype-pdf text-danger float-start me-2" style="font-size: 18px;"></i>
                        Lihat File
                        <p class="text-secondary"> <?= $data['NIM'] ?> - <?= $data['Nama_mahasiswa'] ?> </p>
                    </a>
                    <a href="https://wa.me/<?= $data['No_Telp'] ?>" target="_blank" style="text-decoration:none;"
                        class="text-success">
                        <i class="bi bi-whatsapp text-success float-start me-2" style="font-size: 18px;"></i>
                        <?= $data['No_Telp'] ?>
                    </a>
                </div>
                <div class="card-footer">
                    <small class="float-start">Angkatan: <?= $data['Angkatan'] ?></small>
                    <small class="float-end"> <?= $data['Tanggal_Upload'] ?> </small>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<h5 class='text-center'>Tidak Ada Data</h5>";
    }
}
?>

<div class="shadow-sm card p-2">
    <h2 class="text-center float-end mt-4">Daftar Sertifikat</h2>
    <div class="row">
        <div class="col-md-3 mb-3">
            <a href="halaman_utama.php?page=upload_sertifikat" class="btn btn-primary">Upload Sertifikat</a>
        </div>
        <div class="col"></div>
        <div class="col-md-3 mb-3">
            <datalist id="kegiatan">
                <?php
                $list_kategori = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan");
                while ($data_kegiatan = mysqli_fetch_assoc($list_kategori)) {
                    echo "<option value='{$data_kegiatan['Jenis_Kegiatan']}'></option>";
                }
                ?>
            </datalist>
            <form method="post" class="shadow-sm card">
                <div class="input-group shadow-sm">
                    <input type="search" class="form-control border-0" list="kegiatan" placeholder="Jenis Kegiatan"
                        name="kegiatan" aria-label="Search"
                        value="<?= isset($_POST['kegiatan']) ? $_POST['kegiatan'] : '' ?>">
                    <button type="submit" class="btn btn-success" value="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <button class="judul nav-link flex-grow-1 fw-bold text-warning  active" data-bs-toggle="tab"
                data-bs-target="#menunggu-validasi">Menunggu Validasi</button>
            <button class="judul nav-link flex-grow-1 mx-1 fw-bold text-danger" data-bs-toggle="tab" data-bs-target="#tidak-valid">Tidak
                Valid</button>
            <button class="judul nav-link flex-grow-1 fw-bold text-success" data-bs-toggle="tab" data-bs-target="#valid">Sudah
                Tervalidasi</button>
        </div>
    </nav>
    <div class="tab-content p-3 border bg-light" id="nav-tabContent" style="max-height: 400px; overflow-y: auto;">
        <?php $kegiatan = isset($_POST['kegiatan']) ? $_POST['kegiatan'] : null; ?>
        <div class="tab-pane fade active show" id="menunggu-validasi">
            <?php getSertifikat("Menunggu Validasi", $koneksi, $kegiatan); ?>
        </div>
        <div class="tab-pane fade" id="tidak-valid">
            <?php getSertifikat("Tidak Valid", $koneksi, $kegiatan); ?>
        </div>
        <div class="tab-pane fade" id="valid">
            <?php getSertifikat("Valid", $koneksi, $kegiatan); ?>
        </div>
    </div>
</div>