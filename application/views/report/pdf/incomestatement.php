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
            $this->cell(90,6,"Laporan Laba Rugi",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    function Content($laba_rugi_data, $bln, $tahun, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Laporan Laba Rugi',0,1,'C',1); 
        $this->cell(0,6,'Periode '.getBulan($bln).' '.$tahun,0,1,'C',1); 
        $this->Ln(2);

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,'Pendapatan',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_penjualan_debit = 0;
        $sum_penjualan_kredit = 0;
        if(isset($laba_rugi_data[0][410]))
        {
            foreach ($laba_rugi_data[0][410] as $key => $row)
            {
                if($row['saldo'] > 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'(Rp. '.number_format($row['saldo'],0,',','.').')',0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_penjualan_debit += $row['saldo'];
                } else if($row['saldo'] <= 0) { 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_penjualan_kredit += abs($row['saldo']);
                }
            }
        }

        $total_pendapatan = $sum_penjualan_kredit - $sum_penjualan_debit;

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Penjualan',0,0,'L',1); 
        if($total_pendapatan >= 0) {
            $this->cell(35,6,'Rp. '.number_format($total_pendapatan,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($total_pendapatan),0,',','.').')',0,1,'R',1); 
        }

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);


        // HPP

        $sum_hpp_debit = 0;
        $sum_hpp_kredit = 0;
        if(isset($laba_rugi_data[0][510]))
        {
            foreach ($laba_rugi_data[0][510] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format($row['saldo'],0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_hpp_debit += $row['saldo'];
                } else if($row['saldo'] < 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_hpp_kredit += abs($row['saldo']);
                }
            }
        }

        $total_hpp = $sum_hpp_debit - $sum_hpp_kredit;

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Harga Pokok Penjualan',0,0,'L',1); 
        if($total_hpp >= 0) {
            $this->cell(35,6,'Rp. '.number_format($total_hpp,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($total_hpp).')',0,',','.'),0,1,'R',1);             
        }

        $laba_kotor = $total_pendapatan - $total_hpp;

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',11);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Laba (Rugi) Bruto (Gross Profit)',0,0,'L',1); 
        if($laba_kotor >= 0) {
            $this->cell(35,6,'Rp. '.number_format($laba_kotor,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($laba_kotor).')',0,',','.'),0,1,'R',1);             
        }


        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(0,6,'Beban Operasional',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        
        $sum_beban_operasional_debit = 0;
        $sum_beban_operasional_kredit = 0;
        if(isset($laba_rugi_data[0][610]))
        {
            foreach ($laba_rugi_data[0][610] as $key => $row)
            {   
                if($row['saldo'] >= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format($row['saldo'],0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_beban_operasional_debit += $row['saldo']; 
                } else {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_beban_operasional_kredit += $row['saldo'];  
                }
            }
        }

        $total_beban = $sum_beban_operasional_debit - $sum_beban_operasional_kredit;


        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Beban Operasional',0,0,'L',1); 
        if($total_beban >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($total_beban,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($total_beban).')',0,',','.'),0,1,'R',1);             
        }

        $laba_rugi_operasional = $laba_kotor - $total_beban; 

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',11);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Laba (Rugi) Operasional',0,0,'L',1); 
        if($laba_rugi_operasional >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($laba_rugi_operasional,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($laba_rugi_operasional).')',0,',','.'),0,1,'R',1);             
        }

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(0,6,'Pendapatan (Beban) Diluar Usaha',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_pend_beban_lain_debit = 0;
        $sum_pend_beban_lain_kredit = 0;
        if(isset($laba_rugi_data[0][710]))
        {
            foreach ($laba_rugi_data[0][710] as $key => $row)
            {
                if($row['saldo'] >= 0) { 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format($row['saldo'],0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_pend_beban_lain_debit += $row['saldo'];
                } else if($row['saldo'] < 0) { 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_pend_beban_lain_kredit += abs($row['saldo']);
                }
            }
        }

        $total_pend_beban_lain = $sum_pend_beban_lain_kredit - $sum_pend_beban_lain_debit;

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Pendapatan (Beban) Diluar Usaha',0,0,'L',1); 
        if($total_pend_beban_lain >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($total_pend_beban_lain,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($total_pend_beban_lain).')',0,',','.'),0,1,'R',1);             
        }


        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $laba_rugi_sebelum_pajak = $laba_rugi_operasional + $total_pend_beban_lain;

        $this->setFont('Arial','B',11);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Laba (Rugi) Sebelum Pajak',0,0,'L',1); 
        if($laba_rugi_sebelum_pajak >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($laba_rugi_sebelum_pajak,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($laba_rugi_sebelum_pajak).')',0,',','.'),0,1,'R',1);             
        }

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);


        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(0,6,'Pajak',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_beban_pajak_debit = 0;
        $sum_beban_pajak_kredit = 0;
        if(isset($laba_rugi_data[0][810]))
        {
            foreach ($laba_rugi_data[0][810] as $key => $row)
            {
                if($row['saldo'] >= 0) { 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format($row['saldo'],0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_beban_pajak_debit += $row['saldo'];
                } else if($row['saldo'] < 0) { 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                    $sum_beban_pajak_kredit += abs($row['saldo']);
                }
            }
        }

        $total_pajak = $sum_beban_pajak_debit-$sum_beban_pajak_kredit; 

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Pajak',0,0,'L',1); 
        if($total_pajak >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($total_pajak,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($total_pajak).')',0,',','.'),0,1,'R',1);             
        }



        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $laba_rugi_setelah_pajak = $laba_rugi_sebelum_pajak - $total_pajak; 

        $this->setFont('Arial','B',11);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Laba (Rugi) Setelah Pajak',0,0,'L',1); 
        if($laba_rugi_setelah_pajak >= 0) { 
            $this->cell(35,6,'Rp. '.number_format($laba_rugi_setelah_pajak,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(35,6,'(Rp. '.number_format(abs($laba_rugi_setelah_pajak).')',0,',','.'),0,1,'R',1);             
        }

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

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
$pdf->setTitle('Laporan Laba Rugi '.$profile['name'],true);
$pdf->Content($laba_rugi_data, $bln, $tahun, $profile);
$pdf->Output('laporan-laba-rugi['.date('d-M-Y').'].pdf', 'I');
