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
$username_cookie = $operator['Username'];
$nama_lengkap = $operator['Nama_Lengkap'];
$high_admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Username FROM `pengguna` WHERE `Id_Pengguna` = 1;"));

$username = $_GET['username'];
$data_update = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pegawai INNER JOIN pengguna ON pegawai.Username = pengguna.Username WHERE pegawai.Username = '$username'"));

if (isset($_POST['delete_akun'])) {
    $pass = $_POST['delete_akun'];
    $pass_database = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Password FROM pengguna WHERE Username = '$username'"))['Password'];

    if (password_verify($pass, $pass_database)) {

        // Mulai transaction
        mysqli_query($koneksi, "START TRANSACTION");

        $delete_pengguna = mysqli_query($koneksi, "DELETE FROM pengguna WHERE Username ='$username'");
        $delete_pegawai = mysqli_query($koneksi, "DELETE FROM pegawai WHERE Username = '$username'");

        if (!$delete_pengguna) {
            mysqli_query($koneksi, "ROLLBACK"); // Batalkan jika ada yang gagal
            notifHapus("pegawai", "halaman_utama.php?page=ubah_pegawai&username=" . $username);
        } else {
            if ($username_cookie == $high_admin['Username']) {
                mysqli_query($koneksi, "COMMIT"); // Simpan perubahan
                ?>
                <script>
                    Swal.fire({
                        title: 'Berhasil Menghapus Data',
                        confirmButtonText: 'OK'
                    });
                </script>
                <?php
            } else {
                mysqli_query($koneksi, "COMMIT"); // Simpan perubahan
                setcookie("username", "", time() - 3600, "/");
                setcookie("nama_lengkap", "", time() - 3600, "/");
                setcookie("level_user", "", time() - 3600, "/");
                ?>
                <script>
                    Swal.fire({
                        title: 'Berhasil Menghapus Data',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../login.php';
                        }
                    });
                </script>
                <?php
            }
        }
    } else {
        ?>
        <script>
            Swal.fire({
                title: 'Password Salah',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=ubah_pegawai&username=<?= $username ?>';
                }
            });
        </script>
        <?php
    }
}

if (isset($_POST['tombol_ubah'])) {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $password = htmlspecialchars($_POST['password']);
    $konfirmasi_pass = htmlspecialchars($_POST['konfirmasi_pass']);

    if (!empty($password)) {
        if ($password !== $konfirmasi_pass) {
            ?>
            <script>
                Swal.fire({
                    title: 'Password dan Konfirmasi Password tidak sama',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'halaman_utama.php?page=ubah_pegawai&username=<?= $username ?>';
                    }
                });
            </script>
            <?php
        } else {
            $enskrip = password_hash($password, PASSWORD_DEFAULT);
            $hasil_pengguna = mysqli_query($koneksi, "UPDATE pengguna SET Password = '$enskrip' WHERE Username = '$username'");
            $hasil = mysqli_query($koneksi, "UPDATE pegawai SET Nama_Lengkap='$nama_lengkap' WHERE Username = '$username'");

            if (!$hasil || !$hasil_pengguna) {
                notifGagalUbah("pegawai", "halaman_utama.php?page=ubah_pegawai&username=" . $username);
            } else {
                if ($username_cookie !== $high_admin['Username']) {
                    setcookie('username', $username, time() + (60 * 60 * 24 * 7), '/');
                    setcookie('nama_lengkap', $nama_lengkap, time() + (60 * 60 * 24 * 7), '/');
                }
                notifUbah("pegawai", "halaman_utama.php?page=ubah_pegawai&username=" . $username);
            }
        }
    } else {
        
        $hasil = mysqli_query($koneksi, "UPDATE pegawai SET Nama_Lengkap='$nama_lengkap' WHERE Username = '$username'");
        
        if (!$hasil) {
            notifGagalUbah("pegawai", "halaman_utama.php?page=ubah_pegawai&username=" . $username);
        } else {
            if ($username_cookie !== $high_admin['Username']) {
                setcookie('username', $username, time() + (60 * 60 * 24 * 7), '/');
                setcookie('nama_lengkap', $nama_lengkap, time() + (60 * 60 * 24 * 7), '/');
            }
            notifUbah("pegawai", "halaman_utama.php?page=ubah_pegawai&username=" . $username);
        }
    }
}
?>

<div class="container">
    <div class="row mt-2">
        <div class="col-sm-12 col-md-4 py-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                    <div class="d-flex w-100 align-items-center m-0 justify-content-between">
                        <h5 class="m-0">Daftar Pegawai</h5>
                        <button onclick="window.location.href='halaman_utama.php?page=tambah_pegawai';"
                            class="btn btn-info">+ Tambah</button>
                    </div>
                </a>
                <?php
                $no = 1;
                $data_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai");
                while ($data = mysqli_fetch_assoc($data_pegawai)) {
                    ?>
                    <a href="<?= ($username_cookie == $high_admin['Username']) ? "halaman_utama.php?page=ubah_pegawai&username=" . $data['Username'] : "#" ?>"
                        class="list-group-item list-group-item-action">
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
                        name="username" value="<?= $data_update['Username'] ?>" readonly required>
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="nama_lengkap" value="<?= $data_update['Nama_Lengkap'] ?>" required>
                    <label for="floatingInput">Nama Lengkap</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="password" autocomplete="off">
                    <label for="floatingInput">Ganti Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="konfirmasi_pass" autocomplete="off">
                    <label for="floatingInput">Konfirmasi Password</label>
                </div>
                <?php if ($username_cookie == $username || $username_cookie == $high_admin['Username']) { ?>
                    <button type="button" class="btn btn-danger float-start" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Delete</button>
                <?php } ?>
                <input type="submit" name="tombol_ubah" id="" class="btn btn-warning float-end" value="Update">
            </form>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Password Anda</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="password" name="delete_akun" class="form-control" id="floatingInput"
                                    placeholder="name@example.com" autocomplete="off" autofocus required>
                                <label for="floatingInput">Password</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus Data Saya</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col"></div>
    </div>
</div>