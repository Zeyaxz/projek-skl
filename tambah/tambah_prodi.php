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

    $Prodi = htmlspecialchars($_POST['Prodi']);
    $result = mysqli_num_rows(mysqli_query($koneksi, "SELECT Prodi FROM Prodi WHERE Prodi = '$Prodi'"));

    if ($result > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Prodi sudah terdaftar!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=tambah_Prodi';
                }
            });
        </script>
        <?php
        exit();
    }

    $Id_Prodi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Id_Prodi FROM Prodi ORDER BY Id_Prodi DESC LIMIT 1;"));

    if ($Id_Prodi) {
        $angkaTerakhir = intval(substr($Id_Prodi['Id_Prodi'], 1));
        $noUrut = $angkaTerakhir + 1;
    } else {
        $noUrut = 1;
    }
    $Id = "J" . $noUrut;

    $hasil = mysqli_query($koneksi, "INSERT INTO Prodi VALUES('$Id', '$Prodi')");

    if (!$hasil) {
        notifGagalTambah("Prodi", "halaman_utama.php?page=tambah_Prodi");
    } else {
        notifTambah("Prodi", "halaman_utama.php?page=Prodi");
    }

}
?>
<form action="" method="post">
    <div class="container">
        <div class="row">
            <div class="col"></div>

            <div class="col-10 mt-5 ml-3" style="margin: 0 auto 0 auto;">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="Prodi" required>
                    <label for="floatingInput">Nama Prodi</label>
                </div>
                <input type="submit" name="tombol_tambah" class="btn btn-success float-end" id="" value="tambah">
            </div>

            <div class="col"></div>
        </div>
    </div>
</form>