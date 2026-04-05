<?php
// <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

function showAlert($icon, $message, $url)
{
    ?>
    <script>
        Swal.fire({
            icon: '<?= $icon ?>',
            title: '<?= $message ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?=$url?>';
            }
        });
    </script>
    <?php
}

function notifLogin($url)
{
    showAlert('success', 'Berhasil login', $url);
}

function notifGagalLogin($url)
{
    showAlert('error', 'Gagal Login, Password Salah', $url);
}


function notifLogout($url)
{
    showAlert('success', 'Berhasil logout', $url);
}


function notifUpload( $url)
{
    showAlert('success', "Berhasil upload Sertifikat", $url);
}
function notifGagalUpload( $url)
{
    showAlert('error', "Gagal upload Sertifikat", $url);
}


function notifTambah($item, $url)
{
    showAlert('success', "Berhasil menambahkan $item", $url);
}
function notifGagalTambah($item, $url)
{
    showAlert('error', "Gagal menambahkan $item", $url);
}


function notifUbah($item, $url)
{
    showAlert('success', "Berhasil memperbarui $item", $url);
}
function notifGagalUbah($item, $url)
{
    showAlert('error', "Gagal memperbarui $item", $url);
}


function notifHapus($item, $url)
{
    showAlert('success', "Berhasil menghapus $item", $url);
}
function notifGagalHapus($item, $url)
{
    showAlert('error', "Gagal menghapus $item", $url);
}


