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
            $this->cell(90,6,"Laporan Arus Kas",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    function Content($data_akun, $activitas_operasi, $activitas_investasi, $activitas_pendanaan, $saldo_awal_tahun, $bln, $tahun, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Laporan Arus Kas',0,1,'C',1); 
        $this->cell(0,6,'Periode '.getBulan($bln).' '.$tahun,0,1,'C',1); 

        $this->Ln(2);
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(190,6,'Aktivitas Operasi',0,0,'L',1); 
        //looping data aktivitas operasi
        $this->Ln();
        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $i=1;
        $total_aktivitas_operasi = 0;
        if(!empty($activitas_operasi)) {
            foreach ($activitas_operasi as $act_op) {
                $this->setFont('Arial','',9);
                $this->setFillColor(255,255,255);   
                $this->cell(20,6,$i++,0,0,'C',1);
                $this->cell(80,6,$act_op['nama_kategori'],0,0,'L',1);
    
                $result = 0;
                foreach ($act_op['akun_sumber'] as $nilai) {
                    $result += $nilai['nilai'];
                }
                if($result < 0) { 
                    $this->cell(45,6,'(Rp. '.number_format(abs($result),0,',','.').')',0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                } else {  
                    $this->cell(45,6,'Rp. '.number_format($result,0,',','.'),0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                }
                $total_aktivitas_operasi += $result;
                $this->Ln();
            }
    
            $this->SetLineWidth(0.5);
            $this->Line(10,$this->GetY(),200,$this->GetY());
            $this->SetLineWidth(0);
    
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(145,6,'Total Kas Dari Aktivitas Operasi',0,0,'L',1);
            if($total_aktivitas_operasi < 0) { 
                $this->cell(45,6,'(Rp. '.number_format(abs($total_aktivitas_operasi),0,',','.').')',0,0,'R',1);
            } else {
                $this->cell(45,6,'Rp. '.number_format($total_aktivitas_operasi,0,',','.'),0,0,'R',1);
            }
        }

        $this->Ln();
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(190,6,'Aktivitas Investasi',0,0,'L',1); 

        $this->Ln();
        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $i=1;
        $total_activitas_investasi = 0;
        if(!empty($activitas_investasi)) {
            foreach ($activitas_investasi as $act_inv) {
                $this->setFont('Arial','',9);
                $this->setFillColor(255,255,255);   
                $this->cell(20,6,$i++,0,0,'C',1);
                $this->cell(80,6,$act_inv['nama_kategori'],0,0,'L',1);
    
                $result = 0;
                foreach ($act_inv['akun_sumber'] as $nilai) {
                    $result += $nilai['nilai'];
                }
                if($result < 0) { 
                    $this->cell(45,6,'(Rp. '.number_format(abs($result),0,',','.').')',0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                } else {  
                    $this->cell(45,6,'Rp. '.number_format($result,0,',','.'),0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                }
                $this->Ln();
                $total_activitas_investasi += $result;
            }
    
            $this->SetLineWidth(0.5);
            $this->Line(10,$this->GetY(),200,$this->GetY());
            $this->SetLineWidth(0);
    
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(145,6,'Total Kas Dari Aktivitas Investasi',0,0,'L',1);
            if($total_activitas_investasi < 0) { 
                $this->cell(45,6,'(Rp. '.number_format(abs($total_activitas_investasi),0,',','.').')',0,0,'R',1);
            } else {
                $this->cell(45,6,'Rp. '.number_format($total_activitas_investasi,0,',','.'),0,0,'R',1);
            }
        }

        $this->Ln();
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(190,6,'Aktivitas Pendanaan',0,0,'L',1);

        $this->Ln();
        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0); 

        $i=1;
        $total_activitas_pendanaan = 0;
        if(!empty($activitas_pendanaan)) {
            foreach ($activitas_pendanaan as $act_pend) {
                $this->setFont('Arial','',9);
                $this->setFillColor(255,255,255);   
                $this->cell(20,6,$i++,0,0,'C',1);
                $this->cell(80,6,$act_pend['nama_kategori'],0,0,'L',1);
    
                $result = 0;
                foreach ($act_pend['akun_sumber'] as $nilai) {
                    $result += $nilai['nilai'];
                }
                if($result < 0) { 
                    $this->cell(45,6,'(Rp. '.number_format(abs($result),0,',','.').')',0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                } else {  
                    $this->cell(45,6,'Rp. '.number_format($result,0,',','.'),0,0,'R',1);
                    $this->cell(45,6,'',0,0,'C',1);
                }
                $this->Ln();
                $total_activitas_pendanaan += $result;
            }
    
            $this->SetLineWidth(0.5);
            $this->Line(10,$this->GetY(),200,$this->GetY());
            $this->SetLineWidth(0);
    
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(145,6,'Total Kas Dari Aktivitas Pendanaan',0,0,'L',1);
            if($total_activitas_pendanaan < 0) { 
                $this->cell(45,6,'(Rp. '.number_format(abs($total_activitas_pendanaan),0,',','.').')',0,0,'R',1);
            } else {
                $this->cell(45,6,'Rp. '.number_format($total_activitas_pendanaan,0,',','.'),0,0,'R',1);
            }
        }

        $this->Ln();
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());

        // Perhitungan arus kas berjalan 
        $arus_kas = $total_aktivitas_operasi + $total_activitas_investasi + $total_activitas_pendanaan;
        if($arus_kas < 0) {
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(145,6,'Penurunan Kas',0,0,'L',1);
            $this->cell(45,6,'(Rp. '.number_format(abs($arus_kas),0,',','.').')',0,0,'R',1);
        } else {
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(145,6,'Penambahan Kas',0,0,'L',1);
            $this->cell(45,6,'Rp. '.number_format($arus_kas,0,',','.'),0,0,'R',1);
        }

        $this->Ln();
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);   
        $this->cell(145,6,'Saldo Kas/Bank Awal',0,0,'L',1);
        $this->cell(45,6,'Rp. '.number_format($saldo_awal_tahun,0,',','.'),0,0,'R',1);

        $this->Ln();
        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $saldo_akhir =  $saldo_awal_tahun + $arus_kas;
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);   
        $this->cell(145,6,'Saldo Kas/Bank Akhir',0,0,'L',1);
        if($saldo_akhir < 0) { 
            $this->cell(45,6,'(Rp. '.number_format(abs($saldo_akhir),0,',','.').')',0,0,'R',1);
        } else {
            $this->cell(45,6,'Rp. '.number_format($saldo_akhir,0,',','.'),0,0,'R',1);
        }

        $this->Ln();
        $this->SetLineWidth(0.8);
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
$pdf->setTitle('Laporan Arus Kas '.$profile['name'],true);
$pdf->Content($data_akun, $activitas_operasi, $activitas_investasi, $activitas_pendanaan, $saldo_awal_tahun, $bln, $tahun, $profile);
$pdf->Output('laporan-arus-kas['.date('d-M-Y').'].pdf', 'I');
