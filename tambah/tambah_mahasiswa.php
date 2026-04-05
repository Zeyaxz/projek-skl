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
$username = $operator['Username'];
$nama_lengkap = $operator['Nama_Lengkap'];

if (isset($_POST['tombol_tambah'])) {

    $NIM = htmlspecialchars($_POST['NIM']);
    $cek_NIM = mysqli_query($koneksi, "SELECT NIM FROM pengguna WHERE NIM = '$NIM'");

    if (mysqli_num_rows($cek_NIM) > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Gagal NIM sudah terdaftar.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=tambah_mahasiswa';
                }
            });
        </script>
        <?php
    } else {
        $no_absen = htmlspecialchars($_POST['no_absen']);
        $nama_mahasiswa = htmlspecialchars($_POST['nama_mahasiswa']);
        $no_telp = htmlspecialchars($_POST['no_telp']);
        $email = htmlspecialchars($_POST['email']);
        $Id_Prodi = htmlspecialchars($_POST['Prodi']);
        $kelas = htmlspecialchars($_POST['kelas']);
        $angkatan = htmlspecialchars($_POST['angkatan']);

        $hasil = mysqli_query($koneksi, "INSERT INTO mahasiswa VALUES('$NIM', '$no_absen', '$nama_mahasiswa', '$no_telp', '$email', '$Id_Prodi', '$kelas', '$angkatan')");

        $pass_mahasiswa = "mahasiswa" . $NIM;
        $enkripsi = password_hash($pass_mahasiswa, PASSWORD_DEFAULT);

        $hasil = mysqli_query($koneksi, "INSERT INTO pengguna VALUES(NULL, NULL, '$NIM', '$enkripsi')");

        if (!$hasil) {
            notifGagalTambah("mahasiswa", "halaman_utama.php?page=tambah_mahasiswa");
        } else {
            notifTambah("mahasiswa", "halaman_utama.php?page=mahasiswa");
        }
    }
}
?>

<form action="" method="post">
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-10 mt-5 ml-3" style="margin: 0 auto 0 auto;">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="NIM" required>
                    <label for="floatingInput">NIM</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="no_absen" autocomplete="off" required>
                    <label for="floatingInput">No Absen</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="nama_mahasiswa" required>
                    <label for="floatingInput">Nama Mahasiswa</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@exanple.com"
                        autocomplete="off" name="no_telp" required>
                    <label for="floatingInput">No Telp</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="email" required>
                    <label for="floatingInput">Email</label>
                </div>
                <select name="Prodi" class="form-select form-select-md mb-3" aria-label="Large select example">
                    <option selected>Pilih Prodi</option>
                    <?php
                    $list = mysqli_query($koneksi, "SELECT * FROM Prodi");
                    while ($data = mysqli_fetch_assoc($list)) {
                        ?>
                        <option value="<?= $data['Id_Prodi'] ?>"><?= $data['Prodi'] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="kelas" required>
                    <label for="floatingInput">Kelas</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                        autocomplete="off" name="angkatan" required>
                    <label for="floatingInput">Angkatan</label>
                </div>
                <input type="submit" name="tombol_tambah" class="btn btn-success float-end" id="" value="tambah">
            </div>
            <div class="col"></div>
        </div>
    </div>
</form>