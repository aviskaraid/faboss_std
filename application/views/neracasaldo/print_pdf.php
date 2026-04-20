<?php
$pdf = new PDF_MC_Table('L','mm','A4'); // Landscape agar lega
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 10);

/* ================= HEADER ================= */
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,7, strtoupper($profile['name']),0,1,'C');
$pdf->Cell(0,7,'LAPORAN NERACA SALDO',0,1,'C');
$pdf->Cell(0,7,'Periode '.$periode,0,1,'C');
$pdf->Ln(5);

/* ================= HEADER TABEL ================= */
$pdf->SetFont('Arial','B',10);

// Baris 1
$pdf->Cell(35,10,'No Akun',1,0,'C');
$pdf->Cell(95,10,'Nama Akun',1,0,'C');
$pdf->Cell(48,5,'Saldo Awal',1,0,'C');
$pdf->Cell(48,5,'Mutasi',1,0,'C');
$pdf->Cell(48,5,'Saldo Akhir',1,1,'C');

// Baris 2
$pdf->Cell(35,5,'',0,0);
$pdf->Cell(95,5,'',0,0);

$pdf->Cell(24,5,'Debit',1,0,'C');
$pdf->Cell(24,5,'Kredit',1,0,'C');
$pdf->Cell(24,5,'Debit',1,0,'C');
$pdf->Cell(24,5,'Kredit',1,0,'C');
$pdf->Cell(24,5,'Debit',1,0,'C');
$pdf->Cell(24,5,'Kredit',1,1,'C');

/* ================= DATA ================= */
$pdf->SetFont('Arial','',9);

$total_saldo_awal_debit = 0;
$total_saldo_awal_credit = 0;
$total_mutasi_debit = 0;
$total_mutasi_credit = 0;
$total_saldo_akhir_debit = 0;  
$total_saldo_akhir_credit = 0;

foreach($neracasaldo as $n){

    $sa_debit  = $n['saldo_awal_debit'] ?? 0;
    $sa_kredit = $n['saldo_awal_credit'] ?? 0;
    $mu_debit  = $n['mutasi_debit'] ?? 0;
    $mu_kredit = $n['mutasi_credit'] ?? 0;
    $ak_debit  = $n['saldo_akhir_debit'] ?? 0;
    $ak_kredit = $n['saldo_akhir_credit'] ?? 0;

    // Format angka
    $f_sa_debit  = $sa_debit ? number_format($sa_debit,0,',','.') : '';
    $f_sa_kredit = $sa_kredit ? number_format($sa_kredit,0,',','.') : '';
    $f_mu_debit  = $mu_debit ? number_format($mu_debit,0,',','.') : '';
    $f_mu_kredit = $mu_kredit ? number_format($mu_kredit,0,',','.') : '';
    $f_ak_debit  = $ak_debit ? number_format($ak_debit,0,',','.') : '';
    $f_ak_kredit = $ak_kredit ? number_format($ak_kredit,0,',','.') : '';

    // Baris data (TINGGI FIX = 6 → kunci anti overlap)
    $pdf->Cell(35,6,$n['noakun'],1,0,'C');
    $pdf->Cell(95,6,$n['nama'],1,0,'L');
    $pdf->Cell(24,6,$f_sa_debit,1,0,'R');
    $pdf->Cell(24,6,$f_sa_kredit,1,0,'R');
    $pdf->Cell(24,6,$f_mu_debit,1,0,'R');
    $pdf->Cell(24,6,$f_mu_kredit,1,0,'R');
    $pdf->Cell(24,6,$f_ak_debit,1,0,'R');
    $pdf->Cell(24,6,$f_ak_kredit,1,1,'R');

    // Total
    $total_saldo_awal_debit  += $sa_debit;
    $total_saldo_awal_credit += $sa_kredit;
    $total_mutasi_debit      += $mu_debit;
    $total_mutasi_credit     += $mu_kredit;
    $total_saldo_akhir_debit += $ak_debit;
    $total_saldo_akhir_credit+= $ak_kredit;
}

/* ================= TOTAL ================= */
$pdf->SetFont('Arial','B',10);

$pdf->Cell(130,7,'TOTAL',1,0,'C');
$pdf->Cell(24,7,number_format($total_saldo_awal_debit,0,',','.'),1,0,'R');
$pdf->Cell(24,7,number_format($total_saldo_awal_credit,0,',','.'),1,0,'R');
$pdf->Cell(24,7,number_format($total_mutasi_debit,0,',','.'),1,0,'R');
$pdf->Cell(24,7,number_format($total_mutasi_credit,0,',','.'),1,0,'R');
$pdf->Cell(24,7,number_format($total_saldo_akhir_debit,0,',','.'),1,0,'R');
$pdf->Cell(24,7,number_format($total_saldo_akhir_credit,0,',','.'),1,1,'R');

/* ================= OUTPUT ================= */
$pdf->Output('I','Laporan_Neraca_Saldo_'.$bulan.'_'.$tahun.'.pdf');
exit;
?>