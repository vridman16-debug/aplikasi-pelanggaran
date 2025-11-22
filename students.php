<?php
require_once __DIR__.'/../includes/auth.php';
require_login();
require_once __DIR__.'/../includes/config.php';

if (!empty($_POST['action']) && $_POST['action'] === 'add') {
    $stmt = $pdo->prepare('INSERT INTO students (nis,name,class,gender) VALUES (?,?,?,?)');
    $stmt->execute([$_POST['nis'], $_POST['name'], $_POST['class'], $_POST['gender']]);
    header('Location: students.php'); exit;
}

$students = $pdo->query('SELECT * FROM students ORDER BY class, name')->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Daftar Siswa</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="app-card">
  <h2>Daftar Siswa</h2>
  <form method="post" class="form-inline">
    <input type="hidden" name="action" value="add">
    <input name="nis" placeholder="NIS (opsional)">
    <input name="name" placeholder="Nama siswa" required>
    <input name="class" placeholder="Kelas" required>
    <select name="gender" required>
      <option value="L">Laki-laki</option>
      <option value="P">Perempuan</option>
    </select>
    <button class="btn primary" type="submit">Tambah</button>
  </form>

  <ul class="list">
    <?php foreach ($students as $s): ?>
      <li><?=htmlspecialchars($s['name'])?> — <?=htmlspecialchars($s['class'])?> (<?=htmlspecialchars($s['gender'])?>)</li>
    <?php endforeach; ?>
  </ul>

  <p><a href="dashboard.php" class="btn">Kembali</a></p>
</div>
</body>
</html>
