<?php
require_once "../auth.php";


$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

$NIM_cokkie = $userData['NIM'];
$nama_lengkap = $userData['Nama_mahasiswa'];
$NIM = $_GET['NIM'];

if (isset($_POST['tombol_ubah'])) {
    $password = htmlspecialchars($_POST['password']);
    $konfirmasi_pass = htmlspecialchars($_POST['konfirmasi_pass']);
    if ($password !== $konfirmasi_pass) {
        ?>
        <script>
            Swal.fire({
                title: 'Password dan Konfirmasi Password tidak sama',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=ubah_pass&NIM=<?= $NIM ?>';
                }
            });
        </script>
        <?php
    } else {
        $enskrip = password_hash($password, PASSWORD_DEFAULT);
        $hasil_pengguna = mysqli_query($koneksi, "UPDATE pengguna SET Password = '$enskrip' WHERE NIM = '$NIM'");

        if (!$hasil_pengguna) {
            notifGagalUbah("password", "halaman_utama.php?page=ubah_pass&NIM=' . $NIM . '");
        } else {
            notifUbah("password", "halaman_utama.php?page=dashboard_mahasiswa");
        }
    }
}
?>

<div class="container">
    <div class="row mt-2">
        <div class="col"></div>
        <div class="col-sm-12 col-md-6 py-3">
            <form action="" method="post">
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
                <input type="submit" name="tombol_ubah" id="" class="btn btn-warning float-end" value="Update">
            </form>
        </div>
        <div class="col"></div>

    </div>
</div>