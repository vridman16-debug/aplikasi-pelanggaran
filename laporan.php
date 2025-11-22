<?php
// public/laporan/export_pelanggaran.php
require_once __DIR__ . '/../../includes/auth.php';
require_login();
require_once __DIR__ . '/../../includes/config.php';

// pastikan library fpdf ada
require_once __DIR__ . '/../../library/fpdf.php';

// ambil filter
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$sql = "SELECT v.*, s.name AS student_name FROM violations v JOIN students s ON v.student_id = s.id WHERE 1=1";
$params = [];
if($from){ $sql .= " AND v.violation_date >= ?"; $params[] = $from; }
if($to){ $sql .= " AND v.violation_date <= ?"; $params[] = $to; }
$sql .= " ORDER BY v.violation_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// FPDF generate
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,8,'Laporan Pelanggaran Siswa',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Periode: '.($from?:'Semua').' s/d '.($to?:'Semua'),0,1,'C');
$pdf->Ln(4);

// header table
$pdf->SetFont('Arial','B',10);
$pdf->Cell(28,8,'Tanggal',1,0,'C');
$pdf->Cell(60,8,'Nama Siswa',1,0,'C');
$pdf->Cell(24,8,'Kelas',1,0,'C');
$pdf->Cell(60,8,'Pelanggaran',1,0,'C');
$pdf->Ln();

// content
$pdf->SetFont('Arial','',9);
foreach($rows as $r){
    $pdf->Cell(28,8,$r['violation_date'],1,0);
    $pdf->Cell(60,8,substr($r['student_name'],0,35),1,0);
    $pdf->Cell(24,8,$r['class'],1,0);
    $txt = substr($r['violation_type'],0,60);
    $pdf->Cell(60,8,$txt,1,0);
    $pdf->Ln();
}

// signature area
$pdf->Ln(8);
$pdf->Cell(0,6,'Mengetahui,',0,1);
$pdf->Ln(18);
$pdf->Cell(80,6,'__________________________',0,0,'C');
$pdf->Cell(40,6,'',0,0);
$pdf->Cell(80,6,'__________________________',0,1,'C');
$pdf->Cell(80,6,'(Guru Piket)',0,0,'C');
$pdf->Cell(40,6,'',0,0);
$pdf->Cell(80,6,'(Kepala Sekolah)',0,1,'C');

$pdf->Output('D','laporan_pelanggaran.pdf');
exit;
