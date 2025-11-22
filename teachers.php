<?php
require_once __DIR__.'/../includes/auth.php';
require_login();
require_once __DIR__.'/../includes/config.php';

if (!is_admin()) { echo 'Akses ditolak'; exit; }

if (!empty($_POST['action']) && $_POST['action'] === 'add') {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username,password,name,role) VALUES (?,?,?,?)');
    $stmt->execute([$_POST['username'], $hash, $_POST['name'], 'guru']);
    header('Location: teachers.php'); exit;
}

$teachers = $pdo->query("SELECT * FROM users WHERE role='guru' ORDER BY name")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Guru</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="app-card">
  <h2>Kelola Guru Piket</h2>
  <form method="post" class="form-inline">
    <input type="hidden" name="action" value="add">
    <input name="username" placeholder="username" required>
    <input name="password" placeholder="password" required>
    <input name="name" placeholder="Nama guru" required>
    <button class="btn primary" type="submit">Tambah Guru</button>
  </form>

  <ul class="list">
    <?php foreach ($teachers as $t): ?>
      <li><?=htmlspecialchars($t['name'])?> (<?=htmlspecialchars($t['username'])?>)</li>
    <?php endforeach; ?>
  </ul>

  <p><a href="dashboard.php" class="btn">Kembali</a></p>
</div>
</body>
</html>
