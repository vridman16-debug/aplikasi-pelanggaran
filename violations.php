<?php
// public/violations.php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/config.php'; // pastikan $pdo tersedia

// ambil siswa
$students = $pdo->query("SELECT * FROM students ORDER BY class, name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $violation_date = $_POST['violation_date'] ?? null;
    $student_id = $_POST['student_id'] ?? null;
    $class = $_POST['class'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $notes = $_POST['notes'] ?? '';
    $violation_list = isset($_POST['violation_type']) ? $_POST['violation_type'] : [];
    $violation_type = implode(', ', $violation_list);

    $stmt = $pdo->prepare("INSERT INTO violations (violation_date, student_id, class, gender, violation_type, notes, created_by) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$violation_date, $student_id, $class, $gender, $violation_type, $notes, $_SESSION['user']['id']]);

    header("Location: violations.php?success=1");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Input Pelanggaran</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="app-card">
    <div class="header">
      <div class="brand">PP</div>
      <div>
        <div class="h-title">Input Pelanggaran</div>
        <div class="h-sub">Centang jenis pelanggaran sesuai kondisi</div>
      </div>
    </div>

    <form method="post" class="form">
      <div class="row">
        <div class="col">
          <label>Nama Siswa
            <select name="student_id" id="student_id" required>
              <option value="">-- Pilih Siswa --</option>
              <?php foreach($students as $s): ?>
                <option value="<?= $s['id'] ?>" data-class="<?= htmlspecialchars($s['class']) ?>" data-gender="<?= $s['gender'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['class']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>

        <div class="col">
          <label>Kelas
            <input type="text" name="class" id="class" readonly>
          </label>
        </div>

        <div class="col">
          <label>Tanggal
            <input type="date" name="violation_date" required value="<?= date('Y-m-d') ?>">
          </label>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>Jenis Kelamin
            <input type="text" name="gender" id="gender" readonly>
          </label>
        </div>
      </div>

      <b>Jenis Pelanggaran</b>
      <div class="checkbox-list" style="margin:8px 0 12px 0;">
        <?php
        $listPelanggaran = [
          'Tidak memakai topi',
          'Sepatu tidak sesuai',
          'Seragam tidak lengkap',
          'Tidak membawa kartu pelajar',
          'Atribut upacara tidak lengkap',
          'Terlambat hadir',
          'Rambut tidak rapi',
          'Tidak memakai ikat pinggang',
          'Tidak memakai dasi',
          'Tidak membawa buku pelajaran',
          'Tidur di kelas',
          'Makan/minum di kelas saat pelajaran',
          'Berkelahi / membuat keributan',
          'Menggunakan HP saat pelajaran',
          'Tidak mengerjakan tugas',
          'Membolos',
          'Berbicara kasar / tidak sopan'
        ];
        foreach($listPelanggaran as $p): ?>
          <label class="checkbox-item"><input type="checkbox" name="violation_type[]" value="<?= htmlspecialchars($p) ?>"> <?= htmlspecialchars($p) ?></label>
        <?php endforeach; ?>
      </div>

      <label>Catatan
        <textarea name="notes"></textarea>
      </label>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="reset" class="btn ghost">Batal</button>
        <button type="submit" class="btn primary">Simpan</button>
      </div>
    </form>
  </div>

<script>
document.getElementById('student_id').addEventListener('change', function(){
  const opt = this.options[this.selectedIndex];
  document.getElementById('class').value = opt.dataset.class || '';
  document.getElementById('gender').value = opt.dataset.gender || '';
});
</script>
</body>
</html>
