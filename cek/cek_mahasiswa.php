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

?>


<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-10 mt-5 ml-3" style="margin: 0 auto 0 auto;">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="nane@example.com" name="NIM"
                    value="<?= $data_update['NIM'] ?>" readonly>
                <label for="floatingInput">NIM</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    name="no_absen" autocomplete="off" value="<?= $data_update['No_Absen'] ?>" readonly>
                <label for="floatingInput">No Absen</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="nama_mahasiswa" value="<?= $data_update['Nama_mahasiswa'] ?>" readonly>
                <label for="floatingInput">Nama mahasiswa</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="no_telp" value="<?= $data_update['No_Telp'] ?>" readonly>
                <label for="floatingInput">No Telp</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="email" value="<?= $data_update['Email'] ?>" readonly>
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="Prodi" value="<?= $data_update['Id_Prodi'] ?>" readonly>
                <label for="floatingInput">Prodi</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="kelas" value="<?= $data_update['Kelas'] ?>" readonly>
                <label for="floatingInput">Kelas</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                    autocomplete="off" name="angkatan" value="<?= $data_update['Angkatan'] ?>" readonly>
                <label for="floatingInput">Angkatan</label>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>