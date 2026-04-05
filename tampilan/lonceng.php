<div class="notif-container me-3">
    <button id="notifBtn" class="btn-notif btn btn-light position-relative">
        <i class="bi bi-bell">
            <span id="notifCount" class="badge badgenotif bg-danger badge-hidden">0</span>
        </i>
    </button>
    <div class="notif-popup slideInDown" id="notifPopup">
        <div class="judul-notif">Notifikasi</div>
        <ul id="notifList" class="mt-1"></ul>
    </div>
</div>

<script>
    $(document).ready(function () {

        var NIM = getCookie('NIM');

        function getCookie(name) {
            let cookies = document.cookie.split('; ');
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i].split('=');
                if (cookie[0] === name) {
                    return decodeURIComponent(cookie[1]);
                }
            }
            return null;
        }


        if (NIM) {
            fetchNotifications(NIM); // Pertama kali jalankan saat halaman load
            setInterval(() => fetchNotifications(NIM), 5000); // Jalankan setiap 5 detik
        }

        function fetchNotifications(NIM) {
            if (!NIM) {
                console.error("NIM tidak ditemukan di cookie!");
                return;
            }

            $.ajax({
                url: 'fetch_notifications.php',
                method: 'POST',
                data: { NIM: NIM },
                dataType: 'json',
                success: function (response) {
                    let notifList = $('#notifList');
                    let notifCount = $('#notifCount');

                    notifList.empty();
                    if (response.notifikasi.length > 0) {
                        response.notifikasi.forEach(notif => {
                            notifList.append(
                                `<li class="mt-1">
                                    <a href="halaman_utama.php?page=cek_sertifikat&id=${notif.id}&file=${notif.file}" class="text-decoration-none">
                                        ${notif.pesan} ${notif.status === 'baru' ? '<span class="badge bg-warning">Baru</span>' : ''}
                                    </a>
                                </li>`
                            );

                        });

                        if (response.belumDibaca > 0) {
                            notifCount.text(response.belumDibaca).removeClass('badge-hidden');
                        } else {
                            notifCount.addClass('badge-hidden');
                        }
                    } else {
                        notifCount.addClass('badge-hidden');
                        notifList.append('<li class="text-muted">Tidak ada notifikasi</li>');
                    }
                },
                error: function () {
                    console.error("Gagal mengambil notifikasi.");
                }
            });
        }


        $('#notifBtn').click(function () {
            $('#notifPopup').toggle();

            if (!$('#notifPopup').is(':hidden')) {
                $.ajax({
                    url: 'update_notifications.php',
                    method: 'POST',
                    data: { NIM: NIM },
                    success: function () {
                        $('#notifCount').addClass('badge-hidden'); // Sembunyikan badge setelah AJAX sukses
                        fetchNotifications(NIM); // Perbarui daftar notifikasi setelah diubah statusnya
                    },
                    error: function () {
                        console.error("Gagal memperbarui status notifikasi.");
                    }
                });
            }
        });

        $(document).click(function (event) {
            if (!$(event.target).closest('.notif-container').length) {
                $('#notifPopup').hide();
            }
        });
    });

</script>