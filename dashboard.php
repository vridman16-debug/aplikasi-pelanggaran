<?php
require_once __DIR__.'/../includes/auth.php';
require_login();
$user = $_SESSION['user'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="app-card">
    <h1>Halo, <?=htmlspecialchars($user['name'])?></h1>
    <nav class="menu">
      <a class="menu-item" href="students.php">Daftar Siswa</a>
      <?php if (is_admin()): ?><a class="menu-item" href="teachers.php">Kelola Guru</a><?php endif; ?>
      <a class="menu-item" href="violations.php">Input Pelanggaran</a>
      <a class="menu-item" href="report.php">Laporan</a>
      <a class="menu-item" href="logout.php">Logout</a>
    </nav>
  </div>
</body>
</html>
