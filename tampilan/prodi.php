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

if (isset($_GET['id'])) {
    $Id_Prodi = $_GET['id'];

    $cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mahasiswa WHERE Id_Prodi = '$Id_Prodi'");
    $data = mysqli_fetch_assoc($cek);

    if ($data['total'] > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Gagal menghapus! Prodi masih digunakan oleh mahasiswa.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=Prodi';
                }
            });
        </script>
        <?php
    } else {
        mysqli_query($koneksi, "DELETE FROM Prodi WHERE Id_Prodi = '$Id_Prodi'");
        notifHapus("Prodi", "halaman_utama.php?page=Prodi");
    }
}

?>

<div class="shadow-sm card p-2">
    <h2 class="text-center mt-4">Daftar Prodi</h2>
    <div>
        <a href="halaman_utama.php?page=tambah_Prodi" class="btn btn-success float-end mb-3">+ Tambah Data</a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr class="text-center table-primary">
                <th class="align-middle" scope="col">No</th>
                <th class="align-middle" scope="col">Nama Prodi</th>
                <th class="align-middle" scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data_Prodi = mysqli_query($koneksi, "SELECT * FROM Prodi");
            while ($data = mysqli_fetch_assoc($data_Prodi)) {
                ?>
                <tr class="text-center">
                    <th scope="row align-middle" class="col-1"><?= $no++ ?></th>
                    <td class="col-5 align-middle"><?= $data['Prodi']; ?></td>
                    <td class="col-2 align-middle">
                        <a href="halaman_utama.php?page=ubah_Prodi&id=<?= $data['Id_Prodi'] ?>"
                            class="btn btn-warning"><i class="bi bi-pencil" style="font-size: 20px;"></i></a>
                        <a onclick="return confirm('Yakin mau hapus?');"
                            href="halaman_utama.php?page=Prodi&id=<?= $data['Id_Prodi'] ?>"
                            class="btn btn-danger"><i class="bi bi-trash" style="font-size: 20px;"></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>