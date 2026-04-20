<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends FPDF{
    // Page header
    function Header(){
        if ($this->PageNo() == 1){
            $this->setFont('Arial','I',9);
            $this->setFillColor(255,255,255);
            $this->cell(90,6,'',0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
            $this->Line(10,$this->GetY(),200,$this->GetY());
        }else{
            $this->setFont('Arial','I',9);
            $this->setFillColor(255,255,255);
            $this->cell(90,6,"Neraca Saldo",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
            $this->Ln(3);
        }
    }
 
    function Content($neraca_saldo_data, $bln, $tahun, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Neraca Saldo',0,1,'C',1); 
        $this->cell(0,6,'Periode '.getBulan($bln).' '.$tahun,0,1,'C',1); 

        // Line break
        $this->Ln(3);
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(20,6,'No Akun',1,0,'C',1);
        $this->cell(80,6,'Nama Akun',1,0,'C',1);
        $this->cell(45,6,'Debit',1,0,'C',1);
        $this->cell(45,6,'Kredit',1,0,'C',1);

        $sum_debit = 0;
        $sum_kredit = 0;
        if(isset($neraca_saldo_data[0][110]))
        {
            foreach ($neraca_saldo_data[0][110] as $key => $row)
            {

                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][120]))
        {
            foreach ($neraca_saldo_data[0][120] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][210]))
        {
            foreach ($neraca_saldo_data[0][210] as $key => $row)
            {
                if($row['saldo'] <= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][220]))
        {
            foreach ($neraca_saldo_data[0][220] as $key => $row)
            {
                if($row['saldo'] <= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][310]))
        {
            foreach ($neraca_saldo_data[0][310] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][410]))
        {
            foreach ($neraca_saldo_data[0][410] as $key => $row)
            {
                if($row['saldo'] <= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][510]))
        {
            foreach ($neraca_saldo_data[0][510] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        } 
        
        if(isset($neraca_saldo_data[0][610]))
        {
            foreach ($neraca_saldo_data[0][610] as $key => $row)
            {   
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][710]))
        {
            foreach ($neraca_saldo_data[0][710] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        if(isset($neraca_saldo_data[0][810]))
        {
            foreach ($neraca_saldo_data[0][810] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                        $this->cell(45,6,'',1,0,'C',1);
                    $sum_debit += $row['saldo']; 
                } else {
                        $this->Ln();
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],1,0,'C',1);
                        $this->cell(80,6,$row['nama'],1,0,'L',1);
                        $this->cell(45,6,'',1,0,'C',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),1,0,'R',1);
                    $sum_kredit += $row['saldo'];  
                }
            }
        }

        $this->Ln();
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);   
        $this->cell(100,6,'Jumlah Total',1,0,'R',1);
        $this->cell(45,6,'Rp. '.number_format($sum_debit,0,',','.'),1,0,'R',1);
        $this->cell(45,6,'Rp. '.number_format(abs($sum_kredit),0,',','.'),1,0,'R',1);

    }
 
    // Page footer
    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        //buat garis horizontal
        $this->Line(10,$this->GetY(),200,$this->GetY());
        //Arial italic 9
        $this->SetFont('Arial','I',9);
        $this->Cell(0,10,'Copyright@'.date('Y'),0,0,'L');
        //nomor halaman
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }
}
 
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle('Neraca Saldo '.$profile['name'],true);
$pdf->Content($neraca_saldo_data, $bln, $tahun, $profile);
$pdf->Output('neraca-saldo['.date('d-M-Y').'].pdf', 'I');
