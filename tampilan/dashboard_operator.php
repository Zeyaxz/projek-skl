<?php
require_once "../auth.php";

$userData = getUserData($koneksi);
if (!$userData) {
    redirectToLogin();
}

if (getUserType() !== 'operator') {
    echo "<script>window.location.href='../logout.php'</script>";
    exit;
}

$username = $userData['Username'];
$nama_lengkap = $userData['Nama_Lengkap'];

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="shadow-sm card p-2">
    <div class="container mt-4">
        <div class="row row-cols-4 g-3 text-center">
            <div class="col kotak-data">
                <div
                    class="border p-4 bg-light rounded shadow d-flex flex-column align-items-center justify-content-center h-100">
                    <h2 class="fw-bold">
                        <?php
                        $mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total_mahasiswa FROM mahasiswa;"));
                        echo $mahasiswa["total_mahasiswa"];
                        ?>
                    </h2>
                    <p class="mb-0 d-flex align-items-center">Mahasiswa</p>
                </div>
            </div>
            <div class="col kotak-data">
                <div
                    class="border p-4 bg-light rounded shadow d-flex flex-column align-items-center justify-content-center h-100">
                    <h2 class="fw-bold">
                        <?php
                        $Prodi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total_Prodi FROM prodi;"));
                        echo $Prodi["total_Prodi"];
                        ?>
                    </h2>
                    <p class="mb-0 d-flex align-items-center">Prodi</p>
                </div>
            </div>
            <div class="col kotak-data">
                <div
                    class="border p-4 bg-light rounded shadow d-flex flex-column align-items-center justify-content-center h-100">
                    <h2 class="fw-bold">
                        <?php
                        $mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total_kategori FROM kategori;"));
                        echo $mahasiswa["total_kategori"];
                        ?>
                    </h2>
                    <p class="mb-0 d-flex align-items-center">Kategori</p>
                </div>
            </div>
            <div class="col kotak-data">
                <div
                    class="border p-4 bg-light rounded shadow d-flex flex-column align-items-center justify-content-center h-100">
                    <h2 class="fw-bold">
                        <?php
                        $mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total_kegiatan FROM kegiatan"));
                        echo $mahasiswa["total_kegiatan"];
                        ?>
                    </h2>
                    <p class="mb-0 d-flex align-items-center">Kegiatan</p>
                </div>
            </div>
            <div class="col-12 p-4 h-100  d-flex justify-content-evenly align-items-center">
                <canvas id="pieChart" width="400" height="300" class="border bg-light rounded shadow"></canvas>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        fetch("get_data.php").then(response => response.json()).then(data => {
                            // Cek apakah data valid
                            if (!data || !("valid" in data) || !("pending" in data) || !("tidak_valid" in data)) {
                                console.error("Data tidak valid:", data);
                                return;
                            }

                            const dataPie = {
                                labels: ["Valid", "Menunggu Validasi", "Tidak Valid"],
                                datasets: [{
                                    data: [data.valid, data.pending, data.tidak_valid],
                                    backgroundColor: ["#28a745", "#ffc107", "#dc3545"],
                                    hoverOffset: 5
                                }]
                            };

                            const configPie = {
                                type: "pie",
                                data: dataPie,
                                options: {
                                    responsive: false,
                                    plugins: {
                                        legend: {
                                            position: "top",
                                        },
                                        tooltip: {
                                            enabled: true,
                                            callbacks: {
                                                label: function (tooltipItem) {
                                                    let value = tooltipItem.raw;
                                                    return `${tooltipItem.label}: ${value}`;
                                                }
                                            }
                                        }
                                    }
                                }
                            };

                            new Chart(document.getElementById("pieChart"), configPie);
                        })
                            .catch(error => {
                                console.error("Gagal mengambil data:", error);
                            });
                    });
                </script>
                <div
                    class="border p-4 bg-light rounded shadow d-flex flex-column align-items-center justify-content-center h-100" style="height: 150px !important; width: 250px;">
                    <h2 class="fw-bold">
                        <?php
                        $mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total_sertifikat FROM sertifikat"));
                        echo $mahasiswa["total_sertifikat"];
                        ?>
                    </h2>
                    <p class="mb-0 d-flex align-items-center">Sertifikat</p>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped mt-5 table-hover">
        <thead class="table-primary text-center">
            <tr>
                <th class="align-middle" scope="col">No</th>
                <th class="align-middle" scope="col">kegiatan</th>
                <th class="align-middle" scope="col">Status</th>
                <th class="align-middle" scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $query = "SELECT `Sertifikat`, `Status`, `Id_Sertifikat`, `Jenis_Kegiatan` FROM `sertifikat` INNER JOIN `kegiatan` ON `sertifikat`.`id_kegiatan` = `kegiatan`.`id_kegiatan` WHERE `Status` = 'Menunggu validasi'";
            $result = mysqli_query($koneksi, $query);

            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class='text-center'>
                        <td class="col-1"><?= $no++ ?> </td>
                        <td class="col-3"><?= htmlspecialchars($row['Jenis_Kegiatan']) ?> </td>
                        <td class="col-2"><?= htmlspecialchars($row['Status']) ?> </td>
                        <td class="col-1">
                            <a href="halaman_utama.php?page=cek_sertifikat&id=<?= $row['Id_Sertifikat'] ?>&file=<?= $row['Sertifikat'] ?>"
                                class="btn btn-warning">Edit</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada sertifikat yang divalidasi</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>