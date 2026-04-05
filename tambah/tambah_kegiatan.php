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
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $kegiatan = mysqli_real_escape_string($koneksi, $_POST['kegiatan']);
    $point = htmlspecialchars($_POST['point']);
    $sub_kategori = mysqli_real_escape_string($koneksi, $_POST['subkategori']);

    $cek = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan WHERE Jenis_Kegiatan = '$kegiatan'");
    if (mysqli_num_rows($cek) > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Data Sudah ada di database, Silahkan masukkan Jenis kegiatan baru',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=tambah_kegiatan&kategori=<?=$kategori?>&sub_kategori=<?=$sub_kategori?>';
                }
            });
        </script>
        <?php
    } else {
        $id_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Id_Kategori FROM kategori WHERE Sub_Kategori = '$sub_kategori'"))['Id_Kategori'];

        $hasil = mysqli_query($koneksi, "INSERT INTO kegiatan VALUES(NULL, '$kegiatan', '$point', '$id_kategori')");

        if (!$hasil) {
            notifGagalTambah("kegiatan", "halaman_utama.php?page=tambah_kegiatan");
        } else {
            notifTambah("kegiatan", "halaman_utama.php?page=kegiatan");
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-6 mt-5">
            <select class="form-select form-select-md mb-3 shadow" id="kategori" aria-label="Large select example"
                onchange="pilihKategori(this.value)">
                <option selected>Silahkan Pilih Kategori</option>
                <?php
                $list_kategori = mysqli_query($koneksi, "SELECT Kategori FROM kategori GROUP BY Kategori");
                while ($data_kategori = mysqli_fetch_assoc($list_kategori)) {
                    ?>
                    <option value="<?= $data_kategori['Kategori'] ?>"
                        <?php if(@$_GET['kategori']==$data_kategori['Kategori']){ echo "selected";}?>>
                        <?= $data_kategori['Kategori'] ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col"></div>
    </div>


    <script>
        function pilihKategori(value) {
            window.location.href = 'halaman_utama.php?page=tambah_kegiatan&kategori=' + value;
        }
    </script>

    <?php
    if (isset($_GET['kategori']) && $_GET['kategori'] !== "Silahkan Pilih Kategori") {
        $kategori = $_GET['kategori'];
        ?>
        <div class="row">
            <div class="col"></div>
            <div class="col-6 mt-2">
                <select class="form-select form-select-md mb-3 shadow" id="sub_kategori" aria-label="Large select example" 
                    onchange="pilihSubKategori(this.value)">
                    <option selected>Silahkan Pilih Sub Kategori</option>
                    <?php
                    $list_sub_kategori = mysqli_query($koneksi, "SELECT Sub_Kategori FROM kategori WHERE Kategori='$kategori'");
                    while ($data_sub_kategori = mysqli_fetch_assoc($list_sub_kategori)) {
                        ?>
                        <option value="<?= $data_sub_kategori['Sub_Kategori'] ?>"
                            <?php if(@$_GET['sub_kategori']==$data_sub_kategori['Sub_Kategori']){ echo "selected";}?>>
                            <?= $data_sub_kategori['Sub_Kategori'] ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col"></div>
        </div>

        <script>
            function pilihSubKategori(value) {
                const urlParams = new URLSearchParams(window.location.search);
                const kategori = urlParams.get('kategori');
                window.location.href = 'halaman_utama.php?page=tambah_kegiatan&kategori=' + kategori + '&sub_kategori=' + value;
            }
        </script>
        <?php
    }

    if (isset($_GET['sub_kategori']) && $_GET['sub_kategori'] !== "Silahkan Pilih Sub Kategori") {
        $kategori = $_GET['kategori'];
        $sub_kategori = $_GET['sub_kategori'];
        ?>

        <div class="row">
            <div class="col"></div>
            <div class="col-6 mt-2">
                <form action="" method="post">
                    <input type="hidden" name="kategori" id="" value="<?= $kategori ?>">
                    <input type="hidden" name="subkategori" id="" value="<?= $sub_kategori ?>">

                    <div class="form-floating-md mb-3 shadow">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Nama Kegiatan"
                            name="kegiatan" value="" autofocus required>
                    </div>

                    <div class="form-floating-md mb-3 shadow">
                        <input type="number" class="form-control" id="floatingInput" placeholder="Angka Kredit / Point"
                            name="point" value="" required>
                    </div>

                    <input type="submit" name="tombol_tambah" class="btn btn-success float-end" id="" value="tambah">
                </form>
            </div>
            <div class="col"></div>
        </div>

        <?php
    }
    ?>
</div>
