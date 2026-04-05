<?php
session_start();
require_once "../koneksi.php";

if (!function_exists('getUserType')) {
    function getUserType() {
        if (isset($_COOKIE['username']) && isset($_COOKIE['level_user'])) {
            return 'operator';
        } elseif (isset($_COOKIE['NIM']) && isset($_COOKIE['level_user'])) {
            return 'mahasiswa';
        }
        return false;
    }
}

function getUserData($koneksi) {
    $userType = getUserType();

    if ($userType === 'operator') {
        $username = mysqli_real_escape_string($koneksi, $_COOKIE['username']);
        $query = "SELECT Username, Nama_Lengkap FROM pegawai WHERE Username = '$username'";
    } elseif ($userType === 'mahasiswa') {
        $NIM = mysqli_real_escape_string($koneksi, $_COOKIE['NIM']);
        $query = "SELECT NIM, Nama_mahasiswa FROM mahasiswa WHERE NIM = '$NIM'";
    } else {
        return false;
    }

    return mysqli_fetch_assoc(mysqli_query($koneksi, $query));
}

function redirectToLogin() {
    setcookie("username", "", time() - 3600, "/");
    setcookie("nama_lengkap", "", time() - 3600, "/");
    setcookie("level_user", "", time() - 3600, "/");
    setcookie("NIM", "", time() - 3600, "/");
    header("Location: ../login.php");
    exit;
}
?>
