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
            $this->cell(90,6,"Laporan Neraca",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    function Content($data_akun, $neraca_saldo_data, $laba_ditahan, $laba_tahun_sebelumnya, $set_account, $bln, $tahun, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Laporan Neraca',0,1,'C',1); 
        $this->cell(0,6,'Periode '.getBulan($bln).' '.$tahun,0,1,'C',1); 

        $this->Ln(2);
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);
        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(190,6,'Aset',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $this->cell(5,6,'',0,0,'L',1); 
        $this->cell(185,6,'Aset Lancar',0,1,'L',1); 

        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_aset_lancar = 0;
        if(isset($neraca_saldo_data[0][110]))
        {
            foreach ($neraca_saldo_data[0][110] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                } else {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1); 
                        $this->Ln();
                }
                $sum_aset_lancar += $row['saldo'];  
            }
        }
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Aset Lancar',0,0,'L',1);
        if($sum_aset_lancar >= 0) {
            $this->cell(45,6,'Rp. '.number_format($sum_aset_lancar,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format(abs($sum_aset_lancar),0,',','.').')',0,1,'R',1); 
        }

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(185,6,'Aset Tetap',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_aset_tetap = 0;
        if(isset($neraca_saldo_data[0][120]))
        {
            foreach ($neraca_saldo_data[0][120] as $key => $row)
            {
                if($row['saldo'] >= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                } else {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                }
                $sum_aset_tetap += $row['saldo'];
            }
        }
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);


        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Aset Tetap',0,0,'L',1);
        if($sum_aset_tetap >= 0) {
            $this->cell(45,6,'Rp. '.number_format($sum_aset_tetap,0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format(abs($sum_aset_tetap),0,',','.').')',0,1,'R',1); 
        }  

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $total_aset = $sum_aset_lancar + $sum_aset_tetap; 
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(10,6,'',0,0,'L',1);
        $this->cell(135,6,'Total Aset',0,0,'L',1); 
        if($total_aset >= 0) {
            $this->cell(45,6,'Rp. '.number_format($total_aset,0,',','.'),0,0,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format(abs($total_aset),0,',','.').')',0,0,'R',1); 
        }
        $this->Ln();

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(190,6,'',0,1,'L',1); 
        $this->cell(190,6,'Liabilitas Dan Ekuitas',0,0,'L',1); 
        $this->Ln();
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1); 
        $this->cell(185,6,'Liabilitas Jangka Pendek',0,1,'L',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_liabilitas_jangka_pendek = 0;
        if(isset($neraca_saldo_data[0][210]))
        {
            foreach ($neraca_saldo_data[0][210] as $key => $row)
            {
                if($row['saldo'] <= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                } else {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                }
                $sum_liabilitas_jangka_pendek += $row['saldo'];
            }
        }

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Liabilitas Jangka Pendek',0,0,'L',1);
        if($sum_liabilitas_jangka_pendek <= 0) {
            $this->cell(45,6,'Rp. '.number_format(abs($sum_liabilitas_jangka_pendek),0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format($sum_liabilitas_jangka_pendek,0,',','.').')',0,1,'R',1); 
        } 

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(185,6,'Liabilitas Jangka Panjang',0,1,'L',1);

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_liabilitas_jangka_panjang = 0;
        if(isset($neraca_saldo_data[0][220]))
        {
            foreach ($neraca_saldo_data[0][220] as $key => $row)
            {
                if($row['saldo'] <= 0) {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1);
                        $this->Ln();
                } else {
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);   
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(80,6,$row['nama'],0,0,'L',1);
                        $this->cell(45,6,'(Rp. '.number_format(abs($row['saldo']),0,',','.').')',0,0,'R',1);
                        $this->cell(45,6,'',0,0,'C',1); 
                        $this->Ln();
                }
                $sum_liabilitas_jangka_panjang += $row['saldo'];  
            }
        }

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Liabilitas Jangka Panjang',0,0,'L',1);
        if($sum_liabilitas_jangka_panjang <= 0) {
            $this->cell(45,6,'Rp. '.number_format(abs($sum_liabilitas_jangka_panjang),0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format($sum_liabilitas_jangka_panjang,0,',','.').')',0,1,'R',1); 
        }

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $total_liabilitas = $sum_liabilitas_jangka_pendek + $sum_liabilitas_jangka_panjang;
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Liabilitas',0,0,'L',1);
        if($total_liabilitas <= 0) {
            $this->cell(45,6,'Rp. '.number_format(abs($total_liabilitas),0,',','.'),0,1,'R',1); 
        } else {
            $this->cell(45,6,'(Rp. '.number_format($total_liabilitas,0,',','.').')',0,1,'R',1); 
        } 

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(185,6,'Ekuitas',0,0,'L',1);
        
        $this->Ln();
        $this->setFont('Arial','',9);
        $this->setFillColor(255,255,255);

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        $sum_ekuitas_debit = 0;
        $sum_ekuitas_kredit = 0;
        if(isset($neraca_saldo_data[0][310]))
        {
            foreach ($neraca_saldo_data[0][310] as $key => $row)
            {
                $nilai = $row['saldo'];
                if ($row['noakun'] == $set_account['noakun_lb_ditahan']) {
                    // laba ditahan
                    $nilai += (-1)*$laba_ditahan;
                } else if ($row['noakun'] == $set_account['noakun_lb_sebelumnya']) {
                    // laba ditahan
                    $nilai += (-1)*$laba_tahun_sebelumnya;
                } 


                if($nilai <= 0) {
                    $this->setFont('Arial','',9);
                    $this->setFillColor(255,255,255);   
                    $this->cell(20,6,$row['noakun'],0,0,'C',1);
                    $this->cell(80,6,$row['nama'],0,0,'L',1);
                    $this->cell(45,6,'Rp. '.number_format(abs($nilai),0,',','.'),0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                    $this->Ln();
                    $sum_ekuitas_kredit += $nilai;
                } else {
                    $this->setFont('Arial','',9);
                    $this->setFillColor(255,255,255);   
                    $this->cell(20,6,$row['noakun'],0,0,'C',1);
                    $this->cell(80,6,$row['nama'],0,0,'L',1);
                    $this->cell(45,6,'(Rp. '.number_format(abs($nilai),0,',','.').")",0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1); 
                    $this->Ln();
                    $sum_ekuitas_debit += $nilai;
                }

            }
        }

        $sum_ekuitas = $sum_ekuitas_kredit + $sum_ekuitas_debit;


        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(5,6,'',0,0,'L',1);
        $this->cell(140,6,'Total Ekuitas',0,0,'L',1);

        // $this->cell(20,6,'310-10',0,0,'C',1);
        // $this->cell(80,6,'Modal',0,0,'L',1);
        // $this->cell(45,6,'',0,0,'C',1);
        if($sum_ekuitas >= 0) {
            $this->cell(45,6,'(Rp. '.number_format($sum_ekuitas,0,',','.').')',0,1,'R',1); 
        } else {
            $this->cell(45,6,'Rp. '.number_format(abs($sum_ekuitas),0,',','.'),0,1,'R',1); 
        } 

        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $total_liabilitas_and_ekuitas = $total_liabilitas + $sum_ekuitas;
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(10,6,'',0,0,'L',1);
        $this->cell(135,6,'Total Liabilitas dan Ekuitas',0,0,'L',1); 
        if($total_liabilitas_and_ekuitas >= 0) {
            $this->cell(45,6,'(Rp. '.number_format($total_liabilitas_and_ekuitas,0,',','.').')',0,1,'R',1); 
        } else {
            $this->cell(45,6,'Rp. '.number_format(abs($total_liabilitas_and_ekuitas),0,',','.'),0,1,'R',1); 
        } 

        $this->SetLineWidth(0.5);
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
$pdf->setTitle('Laporan Neraca '.$profile['name'],true);
$pdf->Content($data_akun, $neraca_saldo_data, $laba_ditahan, $laba_tahun_sebelumnya, $set_account, $bln, $tahun, $profile);
$pdf->Output('laporan-neraca['.date('d-M-Y').'].pdf', 'I');
