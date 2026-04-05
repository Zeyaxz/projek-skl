<?php
require_once "../auth.php";


$operator = getUserData($koneksi);
if (!$operator) {
    redirectToLogin();
}

if (getUserType() !== 'operator') {
    echo "<script>window.location.href='../logout.php'</script>";
    exit;
}

$username = $operator['Username'];
$nama_lengkap = $operator['Nama_Lengkap'];
$NIM = $_GET['NIM'] ?? null;

if (isset($_GET['NIM'])) {
    $hapus_notifikasi = true;
    $NIM = $_GET['NIM'];
    // Mulai transaction
    mysqli_query($koneksi, "START TRANSACTION");

    // Ambil Id_Sertifikat
    $sql_sertifikat = "SELECT Id_Sertifikat FROM sertifikat WHERE NIM='$NIM'";
    $result = mysqli_query($koneksi, $sql_sertifikat);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $Id_Sertifikat = $row['Id_Sertifikat'];

        // Hapus dari tabel notifikasi
        $sql_notifikasi = "DELETE FROM notifikasi WHERE Id_Sertifikat='$Id_Sertifikat'";
        $hapus_notifikasi = mysqli_query($koneksi, $sql_notifikasi);
    }

    // Hapus dari tabel sertifikat
    $sql_hapus_sertifikat = "DELETE FROM sertifikat WHERE NIM='$NIM'";
    $hapus_sertifikat = mysqli_query($koneksi, $sql_hapus_sertifikat);

    // Hapus dari tabel pengguna
    $sql_hapus_pengguna = "DELETE FROM pengguna WHERE NIM='$NIM'";
    $hapus_pengguna = mysqli_query($koneksi, $sql_hapus_pengguna);

    // Hapus dari tabel mahasiswa
    $sql_hapus_mahasiswa = "DELETE FROM mahasiswa WHERE NIM='$NIM'";
    $hapus_mahasiswa = mysqli_query($koneksi, $sql_hapus_mahasiswa);

    // Cek apakah semua query berhasil
    if ($hapus_notifikasi && $hapus_sertifikat && $hapus_pengguna && $hapus_mahasiswa) {
        mysqli_query($koneksi, "COMMIT"); // Simpan perubahan
        notifHapus("mahasiswa", "halaman_utama.php?page=mahasiswa");
    } else {
        mysqli_query($koneksi, "ROLLBACK"); // Batalkan jika ada yang gagal
        notifGagalHapus("mahasiswa", "halaman_utama.php?page=mahasiswa");
    }
}

?>


<div class="shadow-sm card p-2">
    <h2 class="text-center mt-4">Daftar mahasiswa</h2>
    <div>
        <a href="halaman_utama.php?page=tambah_mahasiswa" class="btn btn-success ">+ Tambah Data</a>
        <hr>
    </div>
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <?php

        $cari = '';
        $filter_kelas = '';
        $filter_angkatan = '';
        $filter_angkatan_kelas = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cari
            if (!empty($_POST['cari'])) {
                $cari = mysqli_real_escape_string($koneksi, $_POST['cari']);
            }

            // Filter kelas
            if (!empty($_POST['kelas'])) {
                $pilihan = explode(' ', $_POST['kelas']);
                $Prodi = $pilihan[0] ?? '';
                $kelas = $pilihan[1] ?? '';
                

                $filter_kelas = "AND Prodi = '$Prodi' AND Kelas = '$kelas'";
            }

            // Filter angkatan
            if (!empty($_POST['angkatan'])) {
                $angkatan = $_POST['angkatan'];
                $filter_angkatan = "AND Angkatan = '$angkatan'";
                $filter_angkatan_kelas = "WHERE Angkatan = '$angkatan'";
            }

        }
        ?>


        <form method="post" class="d-flex align-items-center justify-content-between w-100" id="filterForm">
            <div class="d-flex">

                <!-- KELAS DROPDOWN -->
                <div class="dropdown me-2">
                    <input type="hidden" name="kelas" id="kelasInput" value="<?= $_POST['kelas'] ?? '' ?>">
                    <button type="button" id="kelasBtn" onclick="toggleDropdown('kelasDropdown')"
                        class="btn btn-outline-dark dropdown-togglee" style="width: 125px;">
                        <?= ($_POST['kelas'] ?? '') ?: 'Kelas' ?>
                    </button>
                    <div id="kelasDropdown" class="dropdown-menuu">
                        <div onclick="setDropdown('kelas', '')">Semua</div>
                        <?php
                        $data_kelas = mysqli_query($koneksi, "SELECT DISTINCT Prodi, Kelas FROM mahasiswa INNER JOIN Prodi USING(Id_Prodi) $filter_angkatan_kelas ORDER BY Prodi ASC, Kelas ASC");
                        while ($data = mysqli_fetch_assoc($data_kelas)) {
                            $kelasVal = $data['Prodi'] . " " . $data['Kelas'];
                            echo "<div onclick=\"setDropdown('kelas', '$kelasVal')\">$kelasVal</div>";
                        }
                        ?>
                    </div>
                </div>

                <!-- ANGKATAN DROPDOWN -->
                <div class="dropdown me-2">
                    <input type="hidden" name="angkatan" id="angkatanInput" value="<?= $_POST['angkatan'] ?? '' ?>">
                    <button type="button" id="angkatanBtn" onclick="toggleDropdown('angkatanDropdown')"
                        class="btn btn-outline-dark dropdown-togglee" style="width: 125px;">
                        <?= ($_POST['angkatan'] ?? '') ?: 'Angkatan' ?>
                    </button>
                    <div id="angkatanDropdown" class="dropdown-menuu">
                        <div onclick="setDropdown('angkatan', '')">Semua</div>
                        <?php
                        $data_angkatan = mysqli_query($koneksi, "SELECT DISTINCT Angkatan FROM mahasiswa");
                        while ($data = mysqli_fetch_assoc($data_angkatan)) {
                            $val = $data['Angkatan'];
                            echo "<div onclick=\"setDropdown('angkatan', '$val')\">$val</div>";
                        }
                        ?>
                    </div>
                </div>

            </div>

            <!-- Search box -->
            <div class="input-group shadow-sm search-box" style="width: 200px;">
                <input type="text" class="form-control border-0" placeholder="Search..." name="cari"
                    value="<?= $_POST['cari'] ?? '' ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <style>
            .dropdown-menuu {
                display: none;
                position: absolute;
                background: #fff;
                border: 1px solid #ddd;
                padding: 5px 0;
                z-index: 1000;
                border-radius: 5px;
                min-width: 100px;
            }

            .dropdown-menuu div {
                padding: 5px 10px;
                cursor: pointer;
            }

            .dropdown-menuu div:hover {
                color: white;
                background-color: black;
            }

            .dropdown-togglee::after {
                content: ' ▼';
                /* bisa juga pakai ▾ */
                font-size: 0.9em;
                margin-left: 5px;
            }
        </style>

        <script>
            function toggleDropdown(id) {
                document.getElementById(id).style.display =
                    document.getElementById(id).style.display === 'block' ? 'none' : 'block';
            }

            function setDropdown(type, value) {
                if (type === 'angkatan') {
                    // Reset kelas juga saat angkatan diganti
                    document.getElementById('kelasInput').value = '';
                    document.getElementById('kelasBtn').innerText = 'Kelas';
                }

                document.getElementById(`${type}Input`).value = value;
                document.getElementById(`${type}Btn`).innerText = value || (type === 'kelas' ? 'Kelas' : 'Angkatan');
                document.getElementById(`${type}Dropdown`).style.display = 'none';
                document.getElementById('filterForm').submit();
            }


            // Tutup semua dropdown jika klik di luar
            document.addEventListener('click', function (e) {
                if (!e.target.matches('.dropdown-togglee')) {
                    document.querySelectorAll('.dropdown-menuu').forEach(function (menu) {
                        menu.style.display = 'none';
                    });
                }
            });
        </script>


    </div>

    <table class="table table-striped table-hover" border="1">
        <thead>
            <tr class="text-center table-primary">
                <th class="align-middle" scope="col">No</th>
                <th class="align-middle" scope="col">NIM</th>
                <th class="align-middle" scope="col">Nama</th>
                <th class="align-middle" scope="col">Kelas</th>
                <th class="align-middle" scope="col">Angkatan</th>
                <th class="align-middle" scope="col">Poin</th>
                <th class="align-middle" scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data_mahasiswa = mysqli_query($koneksi, "SELECT * FROM mahasiswa INNER JOIN Prodi USING(Id_Prodi) WHERE Nama_mahasiswa LIKE '%$cari%' $filter_kelas $filter_angkatan");
            if (mysqli_num_rows($data_mahasiswa) > 0) {
                while ($data = mysqli_fetch_assoc($data_mahasiswa)) {
                    ?>
                    <tr class="text-center">
                        <th scope="row" class="col-1 align-middle"><?= $no++ ?></th>
                        <td class="col-1 align-middle"><?= $data['NIM']; ?></td>
                        <td class="col-4 align-middle"><?= $data['Nama_mahasiswa']; ?></td>
                        <td class="col-1 align-middle"><?= $data['Prodi'] . " " . $data['Kelas']; ?></td>
                        <td class="col-1 align-middle"><?= $data['Angkatan']; ?></td>
                        <td class="col-1 align-middle">
                            <?php
                            $NIM_mahasiswa = $data['NIM'];
                            $mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(Angka_Kredit) AS Point FROM `sertifikat` INNER JOIN kegiatan USING(Id_Kegiatan) WHERE NIM = $NIM_mahasiswa AND Status='Valid';"));
                            $poin = $mahasiswa["Point"] ?? 0;
                            echo $poin;
                            ?>
                        </td>
                        <td class="col-2 align-middle">
                            <a href="halaman_utama.php?page=cek_mahasiswa&NIM=<?= $data['NIM'] ?>" class="btn btn-primary"><i
                                    class="bi bi-search" style="font-size: 20px;"></i></a>
                            <a href="halaman_utama.php?page=ubah_mahasiswa&NIM=<?= $data['NIM'] ?>" class="btn btn-warning"><i
                                    class="bi bi-pencil" style="font-size: 20px;"></i></a>
                            <a href="halaman_utama.php?page=mahasiswa&NIM=<?= $data['NIM'] ?>" class="btn btn-danger"
                                onclick="return confirm('Yakin mau hapus?');"><i class="bi bi-trash"
                                    style="font-size: 20px;"></i></a>
                        </td>
                    </tr>
                    <?php
                }
            } else { ?>
                <tr class="text-center">
                    <th colspan="12" class="align-middle">Data tidak ada</th>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</div>