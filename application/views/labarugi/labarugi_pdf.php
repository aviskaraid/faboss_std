<?php
$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AddPage();

/* ================= FORMAT RUPIAH ================= */
function rupiah($n){
    return $n < 0
        ? '(Rp '.number_format(abs($n),0,',','.').')'
        : 'Rp '.number_format($n,0,',','.');
}

/* ================= HEADER ================= */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,7,$profile['name'],0,1,'C');
$pdf->Cell(0,7,'LAPORAN LABA RUGI',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Periode '.$periode,0,1,'C');
$pdf->Ln(4);

/* ================= SET WIDTH KOLOM ================= */
$w1 = 130; // Nama akun
$w2 = 50;  // Nilai
$rowHeight = 6;

/* ================= FUNGSI STRIPE ================= */
$fill = false;
function rowStripe($pdf,$fill){
    if($fill){
        $pdf->SetFillColor(245,245,245);
    } else {
        $pdf->SetFillColor(255,255,255);
    }
}

/* ================= PENDAPATAN ================= */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'PENDAPATAN',0,1);
$pdf->Ln(1);

$total_pendapatan = 0;

foreach($pendapatan as $group){

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,$group['nama_kel_akun'],0,1);
    $pdf->SetFont('Arial','',10);

    foreach($group['akun'] as $a){
        rowStripe($pdf,$fill);
        $pdf->Cell($w1,$rowHeight,$a['noakun'].' '.$a['nama'],0,0,'L',true);
        $pdf->Cell($w2,$rowHeight,rupiah($a['saldo']),0,1,'R',true);
        $fill = !$fill;
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w1,$rowHeight,'Total '.$group['nama_kel_akun'],0,0);
    $pdf->Cell($w2,$rowHeight,rupiah($group['subtotal']),0,1,'R');
    $pdf->Ln(2);

    $total_pendapatan += $group['subtotal'];
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell($w1,7,'TOTAL PENDAPATAN',0,0);
$pdf->Cell($w2,7,rupiah($total_pendapatan),0,1,'R');
$pdf->Ln(4);

/* ================= HPP ================= */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'HARGA POKOK PENJUALAN',0,1);
$pdf->Ln(1);

$total_hpp = 0;

foreach($hpp as $group){
    foreach($group['akun'] as $a){
        rowStripe($pdf,$fill);
        $pdf->Cell($w1,$rowHeight,$a['noakun'].' '.$a['nama'],0,0,'L',true);
        $pdf->Cell($w2,$rowHeight,rupiah($a['saldo']),0,1,'R',true);
        $fill = !$fill;
    }
    $total_hpp += abs($group['subtotal']);
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell($w1,7,'TOTAL HPP',0,0);
$pdf->Cell($w2,7,rupiah(-$total_hpp),0,1,'R');
$pdf->Ln(2);

/* ================= LABA KOTOR ================= */
$laba_kotor = $total_pendapatan - $total_hpp;

$pdf->SetFont('Arial','B',11);
$pdf->Cell($w1,8,'LABA / RUGI KOTOR',1,0);
$pdf->Cell($w2,8,rupiah($laba_kotor),1,1,'R');
$pdf->Ln(4);

/* ================= BEBAN ================= */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'BEBAN OPERASIONAL',0,1);
$pdf->Ln(1);

$total_beban = 0;

foreach($beban as $group){

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,$group['nama_kel_akun'],0,1);
    $pdf->SetFont('Arial','',10);

    foreach($group['akun'] as $a){
        rowStripe($pdf,$fill);
        $pdf->Cell($w1,$rowHeight,$a['noakun'].' '.$a['nama'],0,0,'L',true);
        $pdf->Cell($w2,$rowHeight,rupiah($a['saldo']),0,1,'R',true);
        $fill = !$fill;
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w1,$rowHeight,'Total '.$group['nama_kel_akun'],0,0);
    $pdf->Cell($w2,$rowHeight,rupiah($group['subtotal']),0,1,'R');
    $pdf->Ln(2);

    $total_beban += abs($group['subtotal']);
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell($w1,7,'TOTAL BEBAN',0,0);
$pdf->Cell($w2,7,rupiah(-$total_beban),0,1,'R');
$pdf->Ln(4);

/* ================= LABA BERSIH ================= */
$laba_bersih = $laba_kotor - $total_beban;

$pdf->SetFont('Arial','B',12);
$pdf->Cell($w1,9,'LABA / RUGI BERSIH',1,0);
$pdf->Cell($w2,9,rupiah($laba_bersih),1,1,'R');

/* ================= OUTPUT ================= */
$filename = 'Laporan_Laba_Rugi_'.$bulan.'_'.$tahun.'.pdf';
$pdf->Output('I', $filename);
exit;
?>
