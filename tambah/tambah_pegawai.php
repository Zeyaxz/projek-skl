<?php
require_once "../auth.php";

$operator = getUserData($koneksi);
if (!$operator) {
    redirectToLogin();
}

if (getUserType() !== 'operator') {
    include '../404.php';
    exit;
}
$username_cookie = $operator['Username'];
$nama_lengkap_cookie = $operator['Nama_Lengkap'];
$high_admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Username FROM `pengguna` WHERE `Id_Pengguna` = 1;"));

if (isset($_POST['tombol_tambah'])) {

    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $konfirmasi_pass = htmlspecialchars($_POST['konfirmasi_pass']);

    $result = mysqli_num_rows(mysqli_query($koneksi, "SELECT Username FROM pengguna WHERE Username = '$username'"));

    if ($result > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Username sudah terdaftar!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=tambah_pegawai';
                }
            });
        </script>
        <?php
    } elseif ($password !== $konfirmasi_pass) {
        ?>
        <script>
            Swal.fire({
                title: 'Password dengan konfirmasi password tidak sama',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=tambah_pegawai';
                }
            });
        </script>
        <?php
    } else {
        $hasil_pegawai = mysqli_query($koneksi, "INSERT INTO pegawai VALUES('$nama_lengkap','$username')");
        $enskrip = password_hash($password, PASSWORD_DEFAULT);
        $hasil_pengguna = mysqli_query($koneksi, "INSERT INTO pengguna VALUES(NULL, '$username', NULL, '$enskrip')");

        if (!$hasil_pengguna) {
            notifGagalTambah("pegawai", "halaman_utama.php?page=tambah_pegawai");
        } else {
            notifTambah("pegawai", "halaman_utama.php?page=ubah_pegawai&username='.$username_cookie.'");
        }
    }
}
?>

<div class="container">
    <div class="row mt-2">
        <div class="col-sm-12 col-md-4 py-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                    <div class="w-100 text-center">
                        <h5>Daftar Pegawai</h5>
                    </div>
                </a>
                <?php
                $no = 1;
                $data_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai");
                while ($data = mysqli_fetch_assoc($data_pegawai)) {
                    ?>
                <a href="<?= ($username_cookie == $high_admin['Username']) ? "halaman_utama.php?page=ubah_pegawai&username=" . $data['Username'] : "#" ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $data['Username'] ?></h5>
                        <small class="text-body-secondary"><?= $data['Nama_Lengkap'] ?></small>
                    </div>
                </a>
                <?php
                }
                ?>

            </div>
        </div>

        <div class="col"></div>
        <div class="col-sm-12 col-md-6 py-3">
            <form action="" method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="nama_lengkap" autofocus required>
                    <label for="floatingInput">Nama Lengkap</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="username" required>
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="password" autocomplete="off" required>
                    <label for="floatingInput">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="konfirmasi_pass" autocomplete="off" required>
                    <label for="floatingInput">Konfirmasi Password</label>
                </div>

                <input type="submit" name="tombol_tambah" class="btn btn-primary float-end" id="" value="+ Tambah">

            </form>
        </div>
        <div class="col"></div>
    </div>
</div>