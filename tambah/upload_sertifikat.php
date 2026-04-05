<?php
require_once "../auth.php";

$user = getUserData($koneksi);
if (!$user) {
    redirectToLogin();
}

if (getUserType() !== 'mahasiswa') {
    echo "<script>window.location.href='../logout.php'</script>";
    exit;
}
$NIM = $user['NIM'];
if (isset($_FILES["sertifikat"])) {
    // Ambil info
    $sub_kategori = $_POST['sub_kategori'];
    $nama_kegiatan = $_POST['kegiatan'];
    $id_kegiatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Id_Kegiatan FROM kegiatan WHERE Jenis_Kegiatan = '$nama_kegiatan'"));
    $id_kegiatan = $id_kegiatan['Id_Kegiatan'];

    // Format jadi aman buat nama file
    $sub_kategori = preg_replace("/[^a-zA-Z0-9]/", "", str_replace(" ", "_", $sub_kategori));
    $nama_kegiatan = preg_replace("/[^a-zA-Z0-9]/", "", str_replace(" ", "_", $nama_kegiatan));

    // Buat timestamp zona Makassar (GMT+8)
    date_default_timezone_set('Asia/Makassar');
    $timestamp = date("Ymd\THis");

    // Nama file final
    $file_name = $NIM . "-" . $sub_kategori . "-" . $nama_kegiatan . "-" . $timestamp . ".pdf";

    $target_dir = "../sertifikat/";
    $target_file = $target_dir.$file_name;

    if (move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $target_file)) {
        $tanggal_upload = date("Y-m-d H:i:s");
        $query = "INSERT INTO sertifikat (Tanggal_Upload, Sertifikat, Status, NIM, Id_Kegiatan) VALUES ('$tanggal_upload', '$file_name', 'Menunggu Validasi', '$NIM', '$id_kegiatan')";
        mysqli_query($koneksi, $query);
        notifUpload("halaman_utama.php?page=sertifikat_mahasiswa");
    } else {
        notifGagalUpload("halaman_utama.php?page=upload_sertifikat");
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
            window.location.href = 'halaman_utama.php?page=upload_sertifikat&kategori=' + value;
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
                window.location.href = 'halaman_utama.php?page=upload_sertifikat&kategori=' + kategori + '&sub_kategori=' + value;
            }
        </script>
        <?php
    }

    if (isset($_GET['sub_kategori']) && $_GET['sub_kategori'] !== "Silahkan Pilih Sub Kategori") {
        $sub_kategori = $_GET['sub_kategori'];
        $id_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Id_Kategori FROM kategori WHERE Sub_Kategori='$sub_kategori'"));
        $id_kategori = $id_kategori['Id_Kategori'];
        ?>
        <div class="row">
            <div class="col"></div>
            <div class="col-6 mt-2">
                <select class="form-select form-select-md mb-3 shadow" id="kegiatan" aria-label="Large select example"
                    onchange="pilihKegiatan(this.value)">
                    <option selected>Silahkan Pilih Kegiatan</option>
                    <?php
                    $list_kegiatan = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan WHERE Id_Kategori='$id_kategori'");
                    
                    while ($data_kegiatan = mysqli_fetch_assoc($list_kegiatan)) {
                        var_dump($_GET['kegiatan'], $data_kegiatan['Jenis_Kegiatan']);
                        ?>
                        <option value="<?= $data_kegiatan['Jenis_Kegiatan']?>"
                            <?php if(@$_GET['kegiatan']==$data_kegiatan['Jenis_Kegiatan']){ echo "selected";}?>>
                            <?= $data_kegiatan['Jenis_Kegiatan'] ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col"></div>
        </div>

        <script>
            function pilihKegiatan(value) {
                const urlParams = new URLSearchParams(window.location.search);
                const kategori = urlParams.get('kategori');
                const sub_kategori = urlParams.get('sub_kategori');
                window.location.href = 'halaman_utama.php?page=upload_sertifikat&kategori=' + kategori + '&sub_kategori=' + sub_kategori + '&kegiatan=' + value;
            }
        </script>
        <?php
    }

    if (isset($_GET['kegiatan']) && $_GET['kegiatan'] !== "Silahkan Pilih Kegiatan") {
        $kegiatan = $_GET['kegiatan'];
        ?>

        <div class="row">
            <div class="col"></div>
            <div class="col-6 mt-2">
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateFile()">
                    <input type="hidden" name="sub_kategori" id="" value="<?= $sub_kategori ?>">
                    <input type="hidden" name="kegiatan" id="" value="<?= $kegiatan ?>">

                    <div class="mb-3">
                        <label for="sertifikat" class="form-label">Upload Sertifikat (PDF, max 2MB)</label>
                        <input type="file" name="sertifikat" id="sertifikat" class="form-control" accept="application/pdf" required>
                        <small id="fileError" class="text-danger"></small>
                    </div>

                    <input type="submit" name="tombol_tambah" class="btn btn-primary" value="Upload">
                </form>

            </div>
            <div class="col"></div>
        </div>

        <?php
    }
    ?>
</div>
<script>
    function validateFile() {
        const fileInput = document.getElementById('sertifikat');
        const fileError = document.getElementById('fileError');
        const file = fileInput.files[0];

        if (!file) {
            fileError.textContent = "Harap pilih file terlebih dahulu.";
            return false;
        }

        if (file.type !== "application/pdf") {
            fileError.textContent = "File harus berformat PDF.";
            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            fileError.textContent = "Ukuran file maksimal 2MB.";
            return false;
        }

        fileError.textContent = "";
        return true;
    }
</script>

