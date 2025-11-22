<?php
// public/statistics.php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/config.php';

// total pelanggaran umum
$totalAll = $pdo->query("SELECT COUNT(*) FROM violations")->fetchColumn();

// ambil per siswa
$stmt = $pdo->query("SELECT s.id, s.name, s.class, COUNT(v.id) AS total_violation FROM students s LEFT JOIN violations v ON s.id = v.student_id GROUP BY s.id ORDER BY total_violation DESC");
$data = $stmt->fetchAll();

$labels = []; $percent = [];
foreach($data as $r){
    $labels[] = $r['name'] . ' (' . $r['class'] . ')';
    $percent[] = $totalAll > 0 ? round(($r['total_violation'] / $totalAll) * 100, 2) : 0;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Statistik Pelanggaran</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="app-card">
    <div class="header">
      <div class="brand">PP</div>
      <div><div class="h-title">Statistik Pelanggaran</div><div class="h-sub">Persentase per siswa</div></div>
    </div>

    <div class="stat-card">
      <canvas id="chartPelanggaran" style="max-height:360px"></canvas>
    </div>
  </div>

<script>
const ctx = document.getElementById('chartPelanggaran').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Persentase Pelanggaran (%)',
            data: <?= json_encode($percent) ?>,
            backgroundColor: 'rgba(30,142,62,0.85)',
            borderRadius: 8,
            barThickness: 22
        }]
    },
    options: {
        responsive: true,
        plugins: {legend:{display:false}},
        scales: {
            y: { beginAtZero:true, max:100 }
        }
    }
});
</script>
</body>
</html>
