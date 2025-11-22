<?php
require_once __DIR__ . '/../includes/auth.php';
if (!empty($_POST)) {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  if (login($u, $p)) {
    header('Location: dashboard.php'); exit;
  } else {
    $error = 'Username atau password salah';
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Aplikasi Pencatatan</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="app-card center">
    <h2>Masuk</h2>
    <?php if (!empty($error)) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>
    <form method="post" class="form">
      <label>Username<input name="username" required></label>
      <label>Password<input name="password" type="password" required></label>
      <div class="buttons">
        <button class="btn primary" type="submit">Masuk</button>
        <a class="btn primary" href="export_pelanggaran.php?from=2025-01-01&to=2025-12-31" target="_blank">Export PDF</a>
      </div>
    </form>
  </div>
</body>
</html>
