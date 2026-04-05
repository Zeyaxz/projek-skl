<?php
require_once 'notif.php';
setcookie('username', '', time() - 3600, '/');
setcookie('NIM', '', time() - 3600, '/');
setcookie('level_user', '', time() - 3600, '/');
setcookie('nama_lengkap', '', time() - 3600, '/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php 
notifLogout("login.php");
?>

</body>
</html>
