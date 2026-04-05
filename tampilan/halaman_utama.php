<?php
ob_start();
require_once "../auth.php";
require_once "../notif.php";

$userData = getUserData($koneksi); //mengambil data user
if (!$userData) { //kondisi jika user tidak login
    redirectToLogin();
}

if (isset($userData['Username'])) {//mengambil data operator
    $username = $userData['Username'];
    $nama_lengkap = $userData['Nama_Lengkap'];
    $role = "operator";
} elseif (isset($userData['NIM'])) {//mengambil data mahasiswa
    $NIM = $userData['NIM'];
    $nama_lengkap = $userData['Nama_mahasiswa'];
    $role = "mahasiswa";
}



if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case "dashboard_operator":
            $title = "Dashboard";
            break;
        case "dashboard_mahasiswa":
            $title = "Dashboard";
            break;

        case "mahasiswa":
            $title = "mahasiswa";
            break;
        case "cek_mahasiswa":
            $title = "Lihat mahasiswa";
            break;
        case "tambah_mahasiswa":
            $title = "Tambah mahasiswa";
            break;
        case "ubah_mahasiswa":
            $title = "Update mahasiswa";
            break;

        case "Prodi":
            $title = "Prodi";
            break;
        case "tambah_Prodi":
            $title = "Tambah Prodi";
            break;
        case "ubah_Prodi":
            $title = "Update Prodi";
            break;

        case "pegawai":
            $title = "Pegawai";
            break;
        case "tambah_pegawai":
            $title = "Tambah Pegawai";
            break;
        case "ubah_pegawai":
            $title = "Update Pegawai";
            break;

        case "kegiatan":
            $title = "Kategori dan Kegiatan";
            break;
        case "tambah_kegiatan":
            $title = "Tambah Kategori Kegiatan";
            break;
        case "ubah_kegiatan":
            $title = "Update Kategori Kegiatan";
            break;

        case "sertifikat_operator":
            $title = "Sertifikat Operator";
            break;
        case "sertifikat_mahasiswa":
            $title = "Sertifikat mahasiswa";
            break;
        case "upload_sertifikat":
            $title = "Upload Sertifikat";
            break;
        case "cek_sertifikat":
            $title = "Update Sertifikat";
            break;

        case "ganti_password":
            $title = "Ganti Password";
            break;
        default:
            $title = "404 - Halaman Tidak Ditemukan";
            break;
    }
} else {
    $title = "404 - Halaman Tidak Ditemukan";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap_icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/mahasiswa.css">
    <link rel="stylesheet" href="../css/lonceng.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="icon" type="image/png" href="../img/icon.png">
    <title><?= $title ?></title>

</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
    <div class="layar">
        <div class="sidebar">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary isi_sidebar sidebar1">
                <?php
                if ($role === "operator") {
                    $link_dashboard = "halaman_utama.php?page=dashboard_operator";
                } elseif ($role === "mahasiswa") {
                    $link_dashboard = "halaman_utama.php?page=dashboard_mahasiswa";
                }
                ?>
                <a href="<?= $link_dashboard ?>"
                    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto logo text-decoration-none">

                    <span class="fs-4 text">
                        <img src="../img/Horizontal-Logo.png" width="180px" height="60px"
                            style="filter: brightness(200%); "></span>
                </a>
                <ul class="nav nav-pills flex-column mb-auto design pt-2">
                    <?php
                    function isActive($page)
                    {
                        return (isset($_GET['page']) && $_GET['page'] === $page) ? 'active' : '';
                    } ?>
                    <?php if ($role === 'operator') { ?>

                        <li class="nav-item pt-2">
                            <a href="halaman_utama.php?page=mahasiswa" class="nav-link <?= isActive('mahasiswa'); ?>"
                                aria-current="page">
                                
                                Mahasiswa
                            </a>
                        </li>
                        <li class="nav-item pt-2">
                            <a href="halaman_utama.php?page=Prodi" class="nav-link  <?= isActive('Prodi'); ?>">
                                Prodi
                            </a>
                        </li>
                        <li class="nav-item pt-2">
                            <a href="halaman_utama.php?page=kegiatan" class="nav-link  <?= isActive('kegiatan'); ?>">
                                Kategori dan Kegiatan
                            </a>
                        </li>
                        <li class="nav-item pt-2">
                            <a class="nav-link <?php echo isActive('sertifikat_operator'); ?>" aria-current="page"
                                href="halaman_utama.php?page=sertifikat_operator">Sertifikat</a>
                        </li>
                    <?php } elseif ($role === 'mahasiswa') { ?>
                        <li class="nav-item pt-2">
                            <a class="nav-link <?php echo isActive('sertifikat_mahasiswa'); ?>" aria-current="page"
                                href="halaman_utama.php?page=sertifikat_mahasiswa">Sertifikat</a>
                        </li>
                    <?php } ?>

                </ul>
            </div>

            <!-- Offcanvas Sidebar nav ke 2 -->
            <div class="offcanvas offcanvas-start bg-body-tertiary isi_sidebar" tabindex="-1" id="sidebarMenu">
                <div class="offcanvas-header">
                    <?php
                    if ($role === "operator") {
                        $link_dashboard = "halaman_utama.php?page=dashboard_operator";
                    } elseif ($role === "mahasiswa") {
                        $link_dashboard = "halaman_utama.php?page=dashboard_mahasiswa";
                    }
                    ?>
                    <a href="<?= $link_dashboard ?>"
                        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto logo text-decoration-none">

                        <span class="fs-4 text">
                            <img src="../img/Horizontal-Logo.png" width="180px" height="60px"
                                style="filter: brightness(200%); "></span>
                    </a>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav nav-pills flex-column">
                        <ul class="nav nav-pills flex-column mb-auto design">


                            <?php if ($role === 'operator') { ?>
                                <li class="nav-item pt-2">

                                    <a href="halaman_utama.php?page=mahasiswa" class="nav-link <?= isActive('mahasiswa'); ?>"
                                        aria-current="page">
                                        <!-- logo -->
                                        mahasiswa
                                    </a>
                                </li>
                                <li class="nav-item pt-2">
                                    <a href="halaman_utama.php?page=Prodi" class="nav-link  <?= isActive('Prodi'); ?>">
                                        <!-- logo -->
                                        Prodi
                                    </a>
                                </li>
                                <li class="nav-item pt-2">
                                    <a href="halaman_utama.php?page=kegiatan"
                                        class="nav-link  <?= isActive('kegiatan'); ?>">
                                        <!-- logo -->
                                        Kategori dan Kegiatan
                                    </a>
                                </li>
                                <li class="nav-item pt-2">
                                    <a href="halaman_utama.php?page=sertifikat_operator"
                                        class="nav-link  <?= isActive('sertifikat_operator'); ?>">
                                        <!-- logo -->
                                        Sertifikat
                                    </a>
                                </li>
                            <?php } elseif ($role === 'mahasiswa') { ?>
                                <li class="nav-item pt-2">
                                    <a href="halaman_utama.php?page=sertifikat_mahasiswa"
                                        class="nav-link  <?= isActive('sertifikat_mahasiswa'); ?>">
                                        <!-- logo -->
                                        Sertifikat
                                    </a>
                                </li>
                            <?php } ?>

                        </ul>
                </div>
            </div>
        </div>
        <div class="konten">
            <div class="header shadow sticky-top d-flex justify-content-between align-items-center px-3">
                <!-- Tombol untuk membuka sidebar -->
                <div>
                    <a class="btn d-md-none btn-sidebar" style="color: Black !important;" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <span class="fs-7 text">
                            <strong> Menu</strong>
                        </span>

                    </a>
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <?php if ($role === "mahasiswa") {
                        include "lonceng.php";
                    } ?>
                    <div class="dropdown ms-auto">
                        <a href="#" class="text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-expanded="false">
                            <strong
                                style="color: black !important;"><?= isset($_COOKIE['nama_lengkap']) ? $_COOKIE['nama_lengkap'] : $nama_lengkap ?></strong>
                        </a>
                        <ul class="dropdown-menu text-small shadow slideInDown p-3">
                            <?php
                            if (@$_COOKIE['level_user'] == 'operator') {
                                $pegawai = $_COOKIE['username'];
                                ?>
                                <li>
                                    <a class="dropdown-item" aria-current="page"
                                        href="halaman_utama.php?page=pegawai&username=<?= $pegawai ?>">Edit Profile</a>
                                </li>
                                <?php
                            } elseif (@$_COOKIE['level_user'] == 'mahasiswa') {
                                $NIM = $_COOKIE['NIM'];
                                ?>
                                <li>
                                    <a class="dropdown-item" aria-current="page"
                                        href="halaman_utama.php?page=ganti_password&NIM=<?= $NIM ?>">Ganti Password</a>
                                </li>
                            <?php } ?>
                            <li class="mt-1"><a class="dropdown-item text-danger" href="../logout.php">logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="isi p-3" style="background-color: rgba(236, 240, 244);">
                <?php
                switch ($_GET['page']) {
                    case "dashboard_operator":
                        include "dashboard_operator.php";
                        break;
                    case "dashboard_mahasiswa":
                        include "dashboard_mahasiswa.php";
                        break;

                    case "mahasiswa":
                        include "mahasiswa.php";
                        break;
                    case "cek_mahasiswa":
                        include "../cek/cek_mahasiswa.php";
                        break;
                    case "tambah_mahasiswa":
                        include "../tambah/tambah_mahasiswa.php";
                        break;
                    case "ubah_mahasiswa":
                        include "../ubah/ubah_mahasiswa.php";
                        break;

                    case "Prodi":
                        include "Prodi.php";
                        break;
                    case "tambah_Prodi":
                        include "../tambah/tambah_Prodi.php";
                        break;
                    case "ubah_Prodi":
                        include "../ubah/ubah_Prodi.php";
                        break;

                    case "pegawai":
                        include "../ubah/ubah_pegawai.php";
                        break;
                    case "tambah_pegawai":
                        include "../tambah/tambah_pegawai.php";
                        break;
                    case "ubah_pegawai":
                        include "../ubah/ubah_pegawai.php";
                        break;

                    case "kegiatan":
                        include "kegiatan.php";
                        break;
                    case "tambah_kegiatan":
                        include "../tambah/tambah_kegiatan.php";
                        break;
                    case "ubah_kegiatan":
                        include "../ubah/ubah_kegiatan.php";
                        break;

                    case "sertifikat_operator":
                        include "sertifikat_operator.php";
                        break;
                    case "sertifikat_mahasiswa":
                        include "sertifikat_mahasiswa.php";
                        break;
                    case "upload_sertifikat":
                        include "../tambah/upload_sertifikat.php";
                        break;
                    case "cek_sertifikat":
                        include "../ubah/cek_sertifikat.php";
                        break;

                    case "ganti_password":
                        include "../ubah/ubah_pass.php";
                        break;

                    default:
                        include '../404.php';
                        break;
                }

                ?>
            </div>
        </div>
    </div>

    <?php
    if (isset($_SESSION['welcome'])) {
        $pesan = $_SESSION['welcome'];
        ?>
        <script>
            Swal.fire({
                title: '<?= $pesan ?>',
                confirmButtonText: 'Oke'
            });
        </script>
        <?php
        unset($_SESSION['welcome']);
    }
    ?>
</body>
<script src="../bootstrap/bootstrap.js"></script>

</html>
<?php ob_end_flush(); ?>