<?php
$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

$pdf->Cell(0,7, strtoupper($profile['name']),0,1,'C');
$pdf->Cell(0,7,'LAPORAN NERACA',0,1,'C');
$pdf->Cell(0,7,'Periode '.$periode,0,1,'C');
$pdf->Ln(5);

function rupiah($n){
    return number_format(abs($n),0,',','.');
}

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,7,'ASET',0,1);

$pdf->SetFont('Arial','',11);

foreach($neraca as $n){
    if($n['tipe']=='A'){
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(0,6,$n['nama_kel_akun'],0,1);

        $pdf->SetFont('Arial','',11);
        foreach($n['akun'] as $a){
            $pdf->Cell(130,6,'   '.$a['noakun'].' '.$a['nama'],0,0);
            $nilai = $a['saldo'] < 0 ? '('.rupiah($a['saldo']).')' : rupiah($a['saldo']);
            $pdf->Cell(40,6,$nilai,0,1,'R');
        }

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(130,6,'Total '.$n['nama_kel_akun'],0,0);
        $pdf->Cell(40,6,rupiah($n['subtotal']),0,1,'R');
        $pdf->Ln(2);
    }
}

$pdf->SetFont('Arial','B',11);
$pdf->Cell(130,7,'TOTAL ASET',0,0);
$pdf->Cell(40,7,rupiah($total_aset),0,1,'R');

$pdf->Ln(4);
$pdf->Cell(0,7,'LIABILITAS DAN EKUITAS',0,1);

$pdf->SetFont('Arial','',11);

foreach($neraca as $n){
    if($n['tipe']=='P'){
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(0,6,$n['nama_kel_akun'],0,1);

        $pdf->SetFont('Arial','',11);
        foreach($n['akun'] as $a){
            $pdf->Cell(130,6,'   '.$a['noakun'].' '.$a['nama'],0,0);
            $nilai = $a['saldo'] < 0 ? '('.rupiah($a['saldo']).')' : rupiah($a['saldo']);
            $pdf->Cell(40,6,$nilai,0,1,'R');
        }

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(130,6,'Total '.$n['nama_kel_akun'],0,0);
        $pdf->Cell(40,6,rupiah($n['subtotal']),0,1,'R');
        $pdf->Ln(2);
    }
}

$pdf->SetFont('Arial','B',11);
$pdf->Cell(130,7,'TOTAL LIABILITAS & EKUITAS',0,0);
$pdf->Cell(40,7,rupiah($total_liabilitas + $total_ekuitas),0,1,'R');

$pdf->Ln(5);


$selisih = $total_aset - ($total_liabilitas + $total_ekuitas);
$pdf->SetFont('Arial','B',11);
if($selisih != 0){
    $pdf->SetTextColor(200,0,0);
    $pdf->Cell(0,7,'NERACA TIDAK BALANCE — Selisih Rp '.rupiah($selisih),0,1,'C');
}
/*
else {
    $pdf->SetTextColor(0,150,0);
    $pdf->Cell(0,7,'NERACA BALANCE',0,1,'C');
}
*/

$pdf->Output('I','Laporan_Neraca_'.$bulan.'_'.$tahun.'.pdf');
exit;
?>
