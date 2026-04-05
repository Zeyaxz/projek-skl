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

$NIM = mysqli_real_escape_string($koneksi, $_GET['NIM']);

$data_update = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE NIM='$NIM'"));

if (isset($_POST['tombol_update'])) {
    $Id_Prodi = htmlspecialchars($_POST['Prodi']);
    if (empty($Id_Prodi)) {
        ?>
        <script>
            Swal.fire({
                title: 'Data Prodi tidak ada di database',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=ubah_mahasiswa&NIM=<?= $NIM ?>';
                }
            });
        </script>
        <?php
    } else {
        $NIMbaru = htmlspecialchars($_POST['NIM']);
        $cek_NIM = mysqli_query($koneksi, "SELECT NIM FROM pengguna WHERE NIM = '$NIMbaru'");

        if (($NIM != $NIMbaru) && mysqli_num_rows($cek_NIM) > 0) {
            ?>
            <script>
                Swal.fire({
                    title: 'Gagal NIM sudah terdaftar.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'halaman_utama.php?page=ubah_mahasiswa&NIM=<?= $NIM ?>';
                    }
                });
            </script>
            <?php
        } else {
            $no_absen = htmlspecialchars($_POST['no_absen']);
            $nama_mahasiswa = htmlspecialchars($_POST['nama_mahasiswa']);
            $no_telp = htmlspecialchars($_POST['no_telp']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $konfirmasi_pass = htmlspecialchars($_POST['konfirmasi_pass']);
            $kelas = htmlspecialchars($_POST['kelas']);
            $angkatan = htmlspecialchars($_POST['angkatan']);

            if (empty($password)) {
                $hasil = mysqli_query($koneksi, "UPDATE mahasiswa SET NIM = '$NIMbaru', No_Absen = '$no_absen', Nama_mahasiswa = '$nama_mahasiswa', No_Telp = '$no_telp', Email = '$email', Id_Prodi = '$Id_Prodi', Kelas = '$kelas', Angkatan = $angkatan WHERE NIM = '$NIM'");

                if (!$hasil) {
                    notifGagalUbah("mahasiswa", "halaman_utama.php?page=ubah_mahasiswa&NIM=$NIM");
                } else {
                    notifUbah("mahasiswa", "halaman_utama.php?page=mahasiswa");
                }
            } else {
                if ($password !== $konfirmasi_pass) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Password dan Konfirmasi Password tidak sama',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'halaman_utama.php?page=ubah_mahasiswa&NIM=<?= $NIM ?>';
                            }
                        });
                    </script>
                    <?php
                } else {
                    $hasil = mysqli_query($koneksi, "UPDATE mahasiswa SET NIM = '$NIMbaru', No_Absen = '$no_absen', Nama_mahasiswa = '$nama_mahasiswa', No_Telp = '$no_telp', Email = '$email', Id_Prodi = '$Id_Prodi', Kelas = '$kelas', Angkatan = $angkatan WHERE NIM = '$NIM'");

                    $enkripsi = password_hash($password, PASSWORD_DEFAULT);

                    $hasil_pengguna = mysqli_query($koneksi, "UPDATE pengguna SET Password = '$enkripsi' WHERE NIM = '$NIM'");

                    if (!$hasil || !$hasil_pengguna) {
                        echo "<script>alert ('Gagal update data mahasiswa');window.location.href='halaman_utama.php?page=ubah_mahasiswa&NIM=$NIM'</script>";
                    } else {
                        echo "<script>alert ('Berhasil update data mahasiswa');window.location.href='halaman_utama.php?page=mahasiswa'</script>";
                    }
                }
            }
        }
    }
}
?>

<form method="post">
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-10 mt-5 ml-3" style="margin: 0 auto 0 auto;">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="nane@example.com"
                        name="NIM" value="<?= $data_update['NIM'] ?>" readonly>
                    <label for="floatingInput">NIM</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="no_absen" autocomplete="off" value="<?= $data_update['No_Absen'] ?>" required>
                    <label for="floatingInput">No Absen</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="nama_mahasiswa" value="<?= $data_update['Nama_mahasiswa'] ?>" required>
                    <label for="floatingInput">Nama mahasiswa</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="no_telp" value="<?= $data_update['No_Telp'] ?>" required>
                    <label for="floatingInput">No Telp</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="email" value="<?= $data_update['Email'] ?>" required>
                    <label for="floatingInput">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" autocomplete="off" class="form-control" name="password" id="floatingInput"
                        placeholder="name@example.com">
                    <label for="floatingInput">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" autocomplete="off" class="form-control" name="konfirmasi_pass" id="floatingInput"
                        placeholder="name@example.com">
                    <label for="floatingInput">Konfirmasi Password</label>
                </div>
                <select name="Prodi" class="form-select form-select-md mb-3" aria-label="Large select example">
                    <option selected>Pilih Prodi</option>
                    <?php
                    $list = mysqli_query($koneksi, "SELECT * FROM Prodi");
                    while ($data = mysqli_fetch_assoc($list)) {
                        ?>

                        <option value="<?php echo $data['Id_Prodi']; ?>"
                            <?= ($data['Id_Prodi'] == $data_update['Id_Prodi']) ? 'selected' : '' ?>>
                            <?= $data['Prodi'] ?>
                        </option>

                        <?php
                    }
                    ?>
                </select>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="kelas" value="<?= $data_update['Kelas'] ?>" required>
                    <label for="floatingInput">Kelas</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="angkatan" value="<?= $data_update['Angkatan'] ?>" required>
                    <label for="floatingInput">Angkatan</label>
                </div>
                <input type="submit" name="tombol_update" class="btn btn-success float-end" id="" value="Update">
            </div>
            <div class="col"></div>
        </div>
    </div>
</form>