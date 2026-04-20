<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends PDF_MC_Table{
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
            $this->cell(90,6,"Laporan Transaksi Biaya",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    
    function Content($dbAll, $tglAwal, $tglAkhir, $profile){
        if ($this->PageNo() == 1){
            // Kop Laporan
            $this->Ln(1);
            $this->setFont('Arial','B',12);
            $this->setFillColor(255,255,255);
            $this->cell(0,6,$profile['name'],0,1,'C',1); 
            $this->cell(0,6,'Laporan Transaksi Biaya',0,1,'C',1); 
            $this->cell(0,6, tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir),0,1,'C',1); 

            // Line break
            $this->Ln(2);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $this->cell(20,6,'Tanggal',1,0,'C',1);
            $this->cell(30,6,'No Transaksi',1,0,'C',1);
            $this->cell(50,6,'Keterangan',1,0,'C',1);
            $this->cell(30,6,'Akun Kas/Bank',1,0,'C',1);
            $this->cell(30,6,'Akun Biaya',1,0,'C',1);
            $this->cell(30,6,'Nilai',1,1,'C',1);
        } else {
            $this->Ln(2);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $this->cell(20,6,'Tanggal',1,0,'C',1);
            $this->cell(30,6,'No Transaksi',1,0,'C',1);
            $this->cell(50,6,'Keterangan',1,0,'C',1);
            $this->cell(30,6,'Akun Kas/Bank',1,0,'C',1);
            $this->cell(30,6,'Akun Biaya',1,0,'C',1);
            $this->cell(30,6,'Nilai',1,1,'C',1);
        }

        // menampilkan datanya
        if (!empty($dbAll)) {
            $this->setFont('Arial','',9);
            $this->SetWidths(Array(20,30,50,30,30,30));
            $this->SetAligns(Array('C','L','L','L','L','R'));
            $this->SetLineHeight(8); 
            $total_nilai = 0;
            foreach ($dbAll as $row) {       
                $this->Row(Array(
                    date("d/n/Y", strtotime($row['tgl'])),
                    $row['no_trans_formatted'],
                    $row['keterangan'],                    
                    $row['nm_kas'],
                    $row['nm_biaya'],
                    'Rp. '.number_format($row['nilai'],0,',','.'),
                ));

                $total_nilai += $row['nilai'];
            }

            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(160,6,'Jumlah Total',1,0,'R',1);
            $this->cell(30,6,'Rp. '.number_format($total_nilai,0,',','.'),1,0,'R',1);
        } else {                
            $this->setFont('Arial','',9);
            $this->setFillColor(255,255,255);   
            $this->cell(190,6,'Tidak ada data',1,1,'L',1);
        }

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
$pdf->setTitle('Laporan Transaksi Biaya '.$profile['name'],true);
$pdf->Content($dbAll, $tglAwal, $tglAkhir, $profile);
$pdf->Output('laporan-transaksi-biaya['.date('d-M-Y').'].pdf', 'I');
