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

if (isset($_GET['Id_Kegiatan'])) {
    $Id_Kegiatan = $_GET['Id_Kegiatan'];

    // Cek apakah ada sertifikat terhubung
    $sql_cek_sertifikat = "SELECT COUNT(*) AS jumlah FROM sertifikat WHERE Id_Kegiatan = '$Id_Kegiatan'";
    $result_cek = mysqli_query($koneksi, $sql_cek_sertifikat);
    $data_cek = mysqli_fetch_assoc($result_cek);

    if ($data_cek['jumlah'] > 0) {
        ?>
        <script>
            Swal.fire({
                title: 'Gagal',
                text: 'Tidak bisa menghapus kegiatan karena masih memiliki sertifikat terkait',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'halaman_utama.php?page=kegiatan';
                }
            });
        </script>
        <?php
    } else {
        // Tidak ada sertifikat, aman untuk dihapus
        $hapus_kegiatan = mysqli_query($koneksi, "DELETE FROM kegiatan WHERE Id_Kegiatan = '$Id_Kegiatan'");

        if ($hapus_kegiatan) {
            notifHapus("kegiatan", "halaman_utama.php?page=kegiatan");
        } else {
            notifGagalHapus("kegiatan", "halaman_utama.php?page=kegiatan");
        }
    }
}

?>


<div class="shadow-sm card p-2">
    <h2 class="text-center mt-4">Daftar Kegiatan</h2>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#laporanModal">
            Buat Laporan
        </button>

        <!-- Modal -->
        <div class="modal fade" id="laporanModal" tabindex="-1" aria-labelledby="laporanModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formLaporan" method="POST" action="export.php?export=Kategori">
                        <div class="modal-header">
                            <h5 class="modal-title" id="laporanModalLabel">Laporan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Pilih Sub Kategori -->
                            <div class="mb-3">
                                <label for="Sub_Kategori" class="form-label">Pilih Sub Kategori</label>
                                <select class="form-select" id="Sub_Kategori" name="Sub_Kategori">
                                    <?php
                                    $list_sub_kategori = mysqli_query($koneksi, "SELECT Sub_Kategori FROM kategori");
                                    while ($data_Sub_Kategori = mysqli_fetch_assoc($list_sub_kategori)) {
                                        ?>
                                        <option value="<?= $data_Sub_Kategori['Sub_Kategori'] ?>">
                                            <?= $data_Sub_Kategori['Sub_Kategori'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" name="export">Generate
                                Laporan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <a href="halaman_utama.php?page=tambah_kegiatan" class="btn btn-success float-end mb-3">+ Tambah Data </a>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr class="text-center table-primary">
                <th class="align-middle" scope="col">No</th>
                <th class="align-middle" scope="col">Jenis Kegiatan</th>
                <th class="align-middle" scope="col">Angka Kredit/Point</th>
                <th class="align-middle" scope="col" colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil data
            $query = mysqli_query($koneksi, "SELECT * FROM kategori INNER JOIN kegiatan ON kategori.Id_Kategori = kegiatan.Id_Kategori ORDER BY CASE WHEN Kategori = 'Wajib' THEN 1 WHEN Kategori = 'Opsional' THEN 2 END, `kegiatan`.`Id_Kategori` ASC, `Kegiatan`.`Id_Kegiatan` ASC");

            $last_kategori_id = null; // Menyimpan ID kategori sebelumnya
            $no = 1; // Nomor urut kegiatan
            while ($baris = mysqli_fetch_assoc($query)) {
                // Jika kategori berubah, tampilkan header kategori
                if ($last_kategori_id !== $baris['Id_Kategori']) {
                    // Tampilkan header baru hanya jika bukan iterasi pertama
                    if ($last_kategori_id != null) {
                        echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                    }
                    ?>
                    <tr class='table-active table-dark tabel-dark'>
                        <td colspan='1' class='fw-bold align-middle text-center' style="width: 100px;">
                            <?= $baris['Kategori'] ?>
                        </td>
                        <td class='fw-bold align-middle text-center'>
                            <?= $baris['Sub_Kategori'] ?>
                        </td>
                        <td></td>
                        <td class='fw-bold align-middle text-center'>
                            <a href='halaman_utama.php?page=ubah_kegiatan&id_kategori=<?= htmlspecialchars($baris['Id_Kategori']) ?>'
                                class='btn btn-warning'><i class="bi bi-pencil" style="font-size: 20px;"></i></a>
                        </td>
                        <td></td>
                    </tr>
                    <?php
                    $no = 1; // Reset nomor kegiatan untuk kategori baru
                }
                ?>
                <!-- Data Kegiatan -->
                <tr class="text-center">
                    <td class="col-2 align-middle"><?= $no++; ?></td>
                    <td class="col-6 align-middle text-center" col-1 align="left">
                        <?= htmlspecialchars($baris['Jenis_Kegiatan']) ?>
                    </td>
                    <td class="col-2 align-middle"><?= htmlspecialchars($baris['Angka_Kredit']) ?></td>
                    <!-- Tombol Aksi -->
                    <td class="col-1 align-middle">
                        <a href="halaman_utama.php?page=ubah_kegiatan&id_kegiatan=<?= htmlspecialchars($baris['Id_Kegiatan']) ?>"
                            class="btn btn-warning"><i class="bi bi-pencil" style="font-size: 20px;"></i></a>
                    </td>
                    <td class="col-1 align-middle">
                        <a href="halaman_utama.php?page=kegiatan&Id_Kegiatan=<?= htmlspecialchars($baris['Id_Kegiatan']) ?>"
                            class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus kegiatan ini?');"><i
                                class="bi bi-trash" style="font-size: 20px;"></i></a>
                    </td>
                </tr>
                <?php
                // Perbarui ID kategori terakhir
                $last_kategori_id = $baris['Id_Kategori'];
            }
            ?>
        </tbody>
    </table>
</div>