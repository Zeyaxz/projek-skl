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

$id = $_GET['id'];
$data_update = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM Prodi WHERE Id_Prodi='$id'"));

if (isset($_POST['tombol_tambah'])) {
    $Prodi = $_POST['Prodi'];
    $cek_Prodi = mysqli_query($koneksi, "SELECT Prodi FROM Prodi WHERE Prodi = '$Prodi'");

    if (($data_update['Prodi'] != $Prodi) && mysqli_num_rows($cek_Prodi) > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Prodi sudah terdaftar.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=ubah_Prodi&id=<?= $id ?>';
                }
            });
        </script>
        <?php
    } else {
        $hasil = mysqli_query($koneksi, "UPDATE Prodi SET Prodi = '$Prodi' WHERE Id_Prodi = '$id'");

        if (!$hasil) {
            notifGagalUbah("Prodi", "halaman_utama.php?page=ubah_Prodi&id=$id");
        } else {
            notifUbah("Prodi", "halaman_utama.php?page=Prodi");
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
                    <input type="text" class="form-control" id="floatingInput" placeholder="nane@example.com"
                        name="Prodi" value="<?= $data_update['Prodi'] ?>" required>
                    <label for="floatingInput">Nama Prodi</label>
                </div>

                <input type="submit" name="tombol_tambah" class="btn btn-success float-end" id="" value="Update">
            </div>
            <div class="col"></div>
        </div>
    </div>
</form>