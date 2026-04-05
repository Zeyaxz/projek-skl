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

if (isset($_GET['id_kegiatan'])) {
    $id_kegiatan = $_GET['id_kegiatan'];
    $data_update = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kategori INNER JOIN Kegiatan ON Kategori.Id_Kategori = Kegiatan.Id_Kategori WHERE Id_Kegiatan = '$id_kegiatan'"));
} elseif (isset($_GET['id_kategori'])) {
    $id_kategori = $_GET['id_kategori'];
    $data_update = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kategori  WHERE Id_Kategori = '$id_kategori'"));
}

if (isset($_POST['tombol_update'])) {
    if (isset($_GET['id_kegiatan'])) {
        $kegiatan = htmlspecialchars($_POST['kegiatan']);
        $point = htmlspecialchars($_POST['point']);
        $cek_kegiatan = mysqli_query($koneksi, "SELECT Jenis_kegiatan FROM kegiatan WHERE Jenis_kegiatan = '$kegiatan'");

        if (($data_update['Jenis_Kegiatan'] != $kegiatan) && mysqli_num_rows($cek_kegiatan) > 0) {
            ?>
            <script>
                Swal.fire({
                    title: 'Kegiatan sudah terdaftar.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'halaman_utama.php?page=ubah_kegiatan&id_kegiatan=<?= $id_kegiatan ?>';
                    }
                });
            </script>
            <?php
        } else {
            $hasil_kegiatan = mysqli_query($koneksi, "UPDATE kegiatan SET Jenis_kegiatan = '$kegiatan', Angka_Kredit = '$point' WHERE Id_Kegiatan = '$id_kegiatan'");
            if (!$hasil_kegiatan) {
                notifGagalUbah("kegiatan", "halaman_utama.php?page=ubah_kegiatan&id_kegiatan=$id_kegiatan");
            } else {
                notifUbah("kegiatan", "halaman_utama.php?page=kegiatan");
            }
        }
    } elseif (isset($_GET['id_kategori'])) {
        $sub_kategori = htmlspecialchars($_POST['sub_kategori']);
        $cek_kategori = mysqli_query($koneksi, "SELECT Sub_Kategori FROM kategori WHERE Sub_Kategori = '$sub_kategori'");

        if (($data_update['Sub_Kategori'] != $sub_kategori) && mysqli_num_rows($cek_kategori) > 0) {
            ?>
            <script>
                Swal.fire({
                    title: 'Sub Kegiatan sudah terdaftar.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'halaman_utama.php?page=ubah_kegiatan&id_kategori=<?= $id_kategori ?>';
                    }
                });
            </script>
            <?php
        } else {
            $hasil_kategori = mysqli_query($koneksi, "UPDATE kategori SET Sub_Kategori = '$sub_kategori' WHERE Id_Kategori = '$id_kategori'");
            if (!$hasil_kategori) {
                notifGagalUbah("kegiatan", "halaman_utama.php?page=ubah_kegiatan&id_kategori=$id_kategori");
            } else {
                notifUbah("kegiatan", "halaman_utama.php?page=kegiatan");
            }
        }
    }

}

?>
<form action="" method="post">
    <div class="container">


        <div class="row">
            <div class="col"></div>
            <div class="col-6 mt-5">
                <div class="form-floating mb-3 shadow">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Kategori" name="kategori"
                        value="<?= $data_update['Kategori'] ?>" autofocus required readonly>
                    <label for="floatingInput-md">Kategori</label>
                </div>

                <div class="form-floating mb-3 shadow">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Kategori"
                        name="sub_kategori" value="<?= $data_update['Sub_Kategori'] ?>" autofocus
                        <?= isset($_GET['id_kegiatan']) ? 'readonly' : '' ?>>
                    <label for="floatingInput-md">Sub Kategori</label>
                </div>
                <?php

                if (isset($_GET['id_kegiatan'])) {
                    $sub_kategori = $data_update['Sub_Kategori'];
                    $list_kegiatan = mysqli_query($koneksi, "SELECT Sub_Kategori, Jenis_Kegiatan FROM kegiatan INNER JOIN Kategori USING(Id_Kategori) WHERE Sub_Kategori='$sub_kategori'");
                    $data_kegiatan = mysqli_fetch_assoc($list_kegiatan);
                    ?>

                    <div class="form-floating mb-3 shadow">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Nama Kegiatan"
                            name="kegiatan" value="<?= $data_update['Jenis_Kegiatan'] ?>" autofocus required>
                        <label for="floatingInput-md">Nama Kegiatan</label>
                    </div>

                    <div class="form-floating mb-3 shadow">
                        <input type="number" class="form-control" id="floatingInput" placeholder="Angka Kredit / Point"
                            name="point" value="<?= $data_update['Angka_Kredit'] ?>" required>
                        <label for="floatingInput">Angka Kredit / Point</label>
                    </div>
                <?php } ?>

                <input type="submit" name="tombol_update" class="btn btn-warning float-end" id="" value="Update">
            </div>
            <div class="col"></div>
        </div>
    </div>
</form>