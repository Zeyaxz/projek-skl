<?php 
session_start();
include "koneksi.php";
require_once 'notif.php';

if (isset($_POST['tombol_login'])) {
  $user = mysqli_real_escape_string($koneksi, $_POST['username']);
  $pass = $_POST['password'];

  $cek_operator = mysqli_query($koneksi, "SELECT Username, Password FROM pengguna WHERE Username='$user'");
  $data_operator = mysqli_fetch_assoc($cek_operator);

  $cek_mahasiswa = mysqli_query($koneksi, "SELECT NIM, Password FROM pengguna WHERE NIM='$user'");
  $data_mahasiswa = mysqli_fetch_assoc($cek_mahasiswa);


  if (mysqli_num_rows($cek_operator) > 0) {
    if (password_verify($pass, $data_operator['Password'])) {
      $user_operator = $data_operator['Username'];
      $nama_operator = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Nama_Lengkap FROM pegawai WHERE Username = '$user_operator'"));
      $nama = $nama_operator['Nama_Lengkap'];

      setcookie('username', $data_operator['Username'], time() + (60 * 60 * 24 * 7), '/');
      setcookie('nama_lengkap', $nama_operator['Nama_Lengkap'], time() + (60 * 60 * 24 * 7), '/');
      setcookie('level_user', 'operator', time() + (60 * 60 * 24 * 7), '/');
      $_SESSION['welcome'] = "Selamat datang kembali, $user_operator!";
      header("Location: tampilan/halaman_utama.php?page=dashboard_operator");
    } else {
      $notif = "gagal";
    }
  } elseif (mysqli_num_rows($cek_mahasiswa) > 0) {
    if (password_verify($pass, $data_mahasiswa['Password'])) {
      $user_mahasiswa = $data_mahasiswa['NIM'];
      $nama_mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Nama_mahasiswa FROM mahasiswa WHERE NIM = '$user_mahasiswa'"));
      $nama = $nama_mahasiswa['Nama_mahasiswa'];
      
      setcookie('NIM', $data_mahasiswa['NIM'], time() + (60 * 60 * 24 * 7), '/');
      setcookie('nama_lengkap', $nama_mahasiswa['Nama_mahasiswa'], time() + (60 * 60 * 24 * 7), '/');
      setcookie('level_user', 'mahasiswa', time() + (60 * 60 * 24 * 7), '/');
      $_SESSION['welcome'] = "Selamat datang kembali, $nama!";
      header("Location: tampilan/halaman_utama.php?page=dashboard_mahasiswa");
    } else {
      $notif = "gagal";
    }
  } else {
    $notif = "gagal";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="/css/login.css"> -->
  <link rel="stylesheet" href="bootstrap/bootstrap.css">
  <link rel="stylesheet" href="../bootstrap/bootstrap_icons/font/bootstrap-icons.css">
  <link rel="icon" type="image/png" href="img/icon.png">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Login</title>
</head>

<body style="background-color:rgb(9, 31, 75) ; min-height: 100vh;">

  <!-- Login 1 - Bootstrap Brain Component -->
  <div class="container">
    <div class="row">
      <div class="col"></div>
      <div class="col-9 col-md-7 col-lg-5 col-xl-4 col-xxl-4 d-flex vh-100 flex-column justify-content-center">
        <center>
          <img src="img/icon.png" alt="" style="filter: brightness(150%)" width="90px" height="100px">
          <h4 style="color: white; font-family: Verdana, Geneva, sans-serif; font-weight:bold;" class="mt-2">INSTIKI</h4>
          <h6 style="color: white; font-weight: 300;">INSTITUT BISNIS DAN TEKNOLOGI INDONESIA</h6>
        </center>
        <div class="bg-white mt-4 p-4 p-md-5 border border-primary-subtle rounded-4"
          style="box-shadow: 0px 0px 10px -2px rgba(255,255,255,0.75); -webkit-box-shadow: 0px 0px 10px -2px rgba(255,255,255,0.75); -moz-box-shadow: 0px 0px 10px -2px rgba(255,255,255,0.75);">

          <form action="" method="post" class="was-validated">
            <div class="row gy-3 gy-md-4 overflow-hidden">
              <div class="col-12">
                <div class="input-group shadow rounded">
                  <span class="input-group-text">
                    <i class="bi bi-person" style="font-size: 20px"></i>
                  </span>
                  <input type="text" class="form-control" name="username" placeholder="username/NIM" id="" required>
                </div>
              </div>
              <div class="col-12">
                <div class="input-group shadow rounded">
                  <span class="input-group-text">
                    <img src="img/lock.png" alt="" width="20px" height="20px">
                  </span>
                  <input type="password" class="form-control" name="password" id="password" placeholder="password"
                    value="" required>
                </div>
              </div>
              <div class="col-12">
                <div class="d-grid">
                  <button class="btn btn-primary btn-lg" name="tombol_login" type="submit" value="login">Log
                    In</button>
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
      <div class="col"></div>
    </div>
  </div>

  <?php
  if (isset($notif)) {
    if ($notif == "gagal") {
      notifGagalLogin("login.php");
    }
  }
  ?>
</body>
<script src="bootstrap/bootstrap.js"></script>

</html>