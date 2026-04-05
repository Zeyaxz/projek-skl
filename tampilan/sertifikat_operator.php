<?php

require_once "../auth.php";

$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

if (isset($userData['Username'])) {
    $username = $userData['Username'];
    $nama_lengkap = $userData['Nama_Lengkap'];
    $role = "operator";
}


// Fungsi untuk mendapatkan sertifikat berdasarkan status dan kegiatan
function getSertifikat($status, $koneksi, $kegiatan = null)
{
    $whereClause = "WHERE Status='$status'";

    if (!empty($kegiatan)) {
        $whereClause .= " AND Jenis_Kegiatan LIKE '%" . $kegiatan . "%'";
    }

    $query = "SELECT * FROM sertifikat
              INNER JOIN kegiatan USING(Id_Kegiatan)
              INNER JOIN kategori USING(Id_Kategori)
              INNER JOIN mahasiswa USING(NIM)
              $whereClause
              ORDER BY Sub_Kategori, Tanggal_Upload ASC";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card border-secondary mb-2">
                <div class="card-header">
                    <button class="btn btn-dark"> <?= $data['Kategori'] ?> </button>
                    <strong> <?= $data['Jenis_Kegiatan'] ?> </strong>
                </div>
                <div class="card-body">
                    <a href="halaman_utama.php?page=cek_sertifikat&id=<?= $data['Id_Sertifikat'] ?>&file=<?= $data['Sertifikat'] ?>"
                        style="text-decoration:none;">
                        <i class="bi bi-filetype-pdf text-danger float-start me-2" style="font-size: 18px;"></i>
                        Lihat File
                        <p class="text-secondary"> <?= $data['NIM'] ?> - <?= $data['Nama_mahasiswa'] ?> </p>
                    </a>
                    <a href="https://wa.me/<?= $data['No_Telp'] ?>" target="_blank" style="text-decoration:none;"
                        class="text-success">
                        <i class="bi bi-whatsapp text-success float-start me-2" style="font-size: 18px;"></i>
                        <?= $data['No_Telp'] ?>
                    </a>
                </div>
                <div class="card-footer">
                    <small class="float-start">Angkatan: <?= $data['Angkatan'] ?></small>
                    <small class="float-end"> <?= $data['Tanggal_Upload'] ?> </small>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<h5 class='text-center'>Tidak Ada Data</h5>";
    }
}
?>

<style>
    .modal.dimmed {
        filter: brightness(70%);
    }
</style>

<div class="shadow-sm card p-2">
    <h2 class="text-center float-end mt-4">Sertifikat</h2>
    <div class="row">
        <div class="col-md-4 col-xs-12 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#laporanModal">
                Buat Laporan
            </button>

            <!-- Modal -->
            <div class="modal fade" id="laporanModal" tabindex="-1" aria-labelledby="laporanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="formLaporan" method="POST" action="export.php?export=Sertifikat">
                            <div class="modal-header">
                                <h5 class="modal-title" id="laporanModalLabel">Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">

                                <!-- Pilih Angkatan -->
                                <div class="mb-3">
                                    <label for="angkatan" class="form-label">Pilih Angkatan</label>
                                    <select class="form-select" id="angkatan" name="angkatan">
                                        <?php
                                        $list_angkatan = mysqli_query($koneksi, "SELECT Angkatan FROM mahasiswa GROUP BY Angkatan");
                                        while ($data_angkatan = mysqli_fetch_assoc($list_angkatan)) {
                                            ?>
                                            <option value="<?= $data_angkatan['Angkatan'] ?>">
                                                <?= $data_angkatan['Angkatan'] ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Pilih Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Pilih Nama</label>
                                    <select class="form-select" id="nama" name="NIM">

                                    </select>
                                </div>

                                <!-- Pilih Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Pilih Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="all">All</option>
                                        <option value="Menunggu Validasi">Menunggu Validasi</option>
                                        <option value="Tidak Valid">Tidak Valid</option>
                                        <option value="Valid">Valid</option>
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


            <!-- Modal Notifikasi -->
            <div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notifModalLabel">Peringatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="notifText"></p> <!-- Tempat teks alert -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    // Saat modal dibuka, langsung load mahasiswa berdasarkan angkatan default (2020)
                    $('#laporanModal').on('shown.bs.modal', function () {
                        var defaultAngkatan = $('#angkatan').val();
                        loadmahasiswa(defaultAngkatan);
                    });

                    // Saat angkatan berubah, update daftar mahasiswa
                    $('#angkatan').change(function () {
                        var angkatan = $(this).val();
                        loadmahasiswa(angkatan);
                    });

                    function loadmahasiswa(angkatan) {
                        $.ajax({
                            url: 'get_mahasiswa.php',
                            type: 'POST',
                            data: { angkatan: angkatan },
                            success: function (data) {
                                $('#nama').html(data);

                                // Pilih mahasiswa pertama secara otomatis
                                $('#nama').val($('#nama option:first').val()).change();
                            }
                        });
                    }
                });

                $(document).ready(function () {
                    $("#formLaporan").submit(function (e) {
                        e.preventDefault(); // Mencegah form submit langsung

                        var NIM = $("#nama").val();
                        var status = $("#status").val();

                        if (NIM === "" || status === "") {
                            alert("⚠️ Pilih nama mahasiswa dan status terlebih dahulu!");
                            return;
                        }

                        // Kirim data ke cek_status_sertifikat.php untuk pengecekan
                        $.ajax({
                            url: "cek_status_sertifikat.php",
                            data: { NIM: NIM, status: status },
                            type: "POST",
                            success: function (response) {
                                if (response == "ok") {
                                    $("form")[0].submit(); // Submit form jika ada sertifikat
                                } else {
                                    $("#notifText").text("❌ mahasiswa ini tidak memiliki sertifikat dengan status yang dipilih!");
                                    $("#notifModal").modal("show");
                                }
                            }
                        });
                    });
                });

                document.addEventListener("DOMContentLoaded", function () {
                    var notifModal = document.getElementById("notifModal");

                    notifModal.addEventListener("show.bs.modal", function () {
                        // Saat modal notifikasi muncul, buat modal sebelumnya lebih gelap
                        var modalAktif = document.querySelector(".modal.show:not(#notifModal)");
                        if (modalAktif) {
                            modalAktif.classList.add("dimmed");
                        }
                    });

                    notifModal.addEventListener("hidden.bs.modal", function () {
                        // Saat modal notifikasi ditutup, kembalikan modal sebelumnya ke normal
                        var modalAktif = document.querySelector(".modal.dimmed");
                        if (modalAktif) {
                            modalAktif.classList.remove("dimmed");
                        }
                    });
                });

            </script>
        </div>
        <div class="col"></div>
        <div class="col-md-3 mb-3">
            <datalist id="kegiatan">
                <?php
                $list_kategori = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan");
                while ($data_kegiatan = mysqli_fetch_assoc($list_kategori)) {
                    echo "<option value='{$data_kegiatan['Jenis_Kegiatan']}'></option>";
                }
                ?>
            </datalist>
            <form method="post" class="shadow-sm card">
                <div class="input-group shadow-sm">
                    <input type="search" class="form-control border-0" list="kegiatan" placeholder="Jenis Kegiatan"
                        name="kegiatan" aria-label="Search"
                        value="<?= isset($_POST['kegiatan']) ? $_POST['kegiatan'] : '' ?>">
                    <button type="submit" class="btn btn-success" value="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <button class="judul nav-link flex-grow-1 fw-bold text-warning  active" data-bs-toggle="tab"
                data-bs-target="#menunggu-validasi">Menunggu Validasi</button>
            <button class="judul nav-link flex-grow-1 mx-1 fw-bold text-danger" data-bs-toggle="tab"
                data-bs-target="#tidak-valid">Tidak
                Valid</button>
            <button class="judul nav-link flex-grow-1 fw-bold text-success" data-bs-toggle="tab"
                data-bs-target="#valid">Sudah
                Tervalidasi</button>
        </div>
    </nav>
    <div class="tab-content p-3 border bg-light" id="nav-tabContent" style="max-height: 400px; overflow-y: auto;">
        <?php $kegiatan = isset($_POST['kegiatan']) ? $_POST['kegiatan'] : null; ?>
        <div class="tab-pane fade active show" id="menunggu-validasi">
            <?php getSertifikat("Menunggu Validasi", $koneksi, $kegiatan); ?>
        </div>
        <div class="tab-pane fade" id="tidak-valid">
            <?php getSertifikat("Tidak Valid", $koneksi, $kegiatan); ?>
        </div>
        <div class="tab-pane fade" id="valid">
            <?php getSertifikat("Valid", $koneksi, $kegiatan); ?>
        </div>
    </div>
</div>
