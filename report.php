<?php
require_once __DIR__.'/../includes/auth.php';
require_login();
require_once __DIR__.'/../includes/config.php';

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$sql = 'SELECT v.*, s.name AS student_name FROM violations v JOIN students s ON v.student_id = s.id WHERE 1=1';
$params = [];
if ($from) { $sql .= ' AND v.violation_date >= ?'; $params[] = $from; }
if ($to)   { $sql .= ' AND v.violation_date <= ?'; $params[] = $to; }
$sql .= ' ORDER BY v.violation_date DESC LIMIT 200';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Laporan Pelanggaran</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="app-card">
  <h2>Laporan Pelanggaran</h2>
  <form method="get" class="form-inline">
    <label>Dari <input type="date" name="from" value="<?=htmlspecialchars($from)?>"></label>
    <label>Sampai <input type="date" name="to" value="<?=htmlspecialchars($to)?>"></label>
    <button class="btn" type="submit">Filter</button>
  </form>

  <table class="report-table">
    <thead><tr><th>Tanggal</th><th>Nama</th><th>Kelas</th><th>Pelanggaran</th><th>Catatan</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['violation_date'])?></td>
        <td><?=htmlspecialchars($r['student_name'])?></td>
        <td><?=htmlspecialchars($r['class'])?></td>
        <td><?=htmlspecialchars($r['violation_type'])?></td>
        <td><?=htmlspecialchars($r['notes'])?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div style="margin-top:20px">
    <p>Mengetahui,</p>
    <p>__________________________</p>
    <p>(Nama & Tanda Tangan)</p>
    <p>Tanggal: <?= date('Y-m-d') ?></p>
  </div>

  <p><a href="dashboard.php" class="btn">Kembali</a></p>
</div>
</body>
</html>
