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
            $this->cell(90,6,"Laporan Transaksi Kas Bank",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    
    function Content($dbAll, $tglAwal, $tglAkhir, $profile, $nama){
        if ($this->PageNo() == 1){
            // Kop Laporan
            $this->Ln(1);
            $this->setFont('Arial','B',12);
            $this->setFillColor(255,255,255);
            $this->cell(0,6,$profile['name'],0,1,'C',1); 
            $this->cell(0,6,'Laporan Transaksi '.$nama['nm'],0,1,'C',1); 
            $this->cell(0,6, tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir),0,1,'C',1); 

            // Line break
            $this->Ln(2);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $this->cell(30,6,'Tanggal',1,0,'C',1);
            $this->cell(40,6,'No Transaksi',1,0,'C',1);
            $this->cell(60,6,'Keterangan',1,0,'C',1);
            $this->cell(30,6,'Debit',1,0,'C',1);
            $this->cell(30,6,'Credit',1,1,'C',1);
        } else {
            $this->Ln(2);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $this->cell(30,6,'Tanggal',1,0,'C',1);
            $this->cell(40,6,'No Transaksi',1,0,'C',1);
            $this->cell(60,6,'Keterangan',1,0,'C',1);
            $this->cell(30,6,'Debit',1,0,'C',1);
            $this->cell(30,6,'Credit',1,1,'C',1);
        }

        // menampilkan datanya
        if (!empty($dbAll)) {
            $this->setFont('Arial','',9);
            $this->SetWidths(Array(30,40,60,30,30));
            $this->SetAligns(Array('C','L','L','R','R'));
            $this->SetLineHeight(8); 
            $total_debit = 0;
            $total_credit = 0;
            foreach ($dbAll as $row) {       
                $this->Row(Array(
                    date("d/n/Y", strtotime($row['tgl'])),
                    $row['no_trans_formatted'],
                    $row['keterangan'],
                    'Rp. '.number_format($row['debit'],0,',','.'),
                    'Rp. '.number_format($row['credit'],0,',','.'),
                ));

                $total_debit += $row['debit'];
                $total_credit += $row['credit'];
            }

            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);   
            $this->cell(130,6,'Jumlah Total',1,0,'R',1);
            $this->cell(30,6,'Rp. '.number_format($total_debit,0,',','.'),1,0,'R',1);
            $this->cell(30,6,'Rp. '.number_format($total_credit,0,',','.'),1,0,'R',1);
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
$pdf->setTitle('Laporan Kas Bank '.$profile['name'],true);
$pdf->Content($dbAll, $tglAwal, $tglAkhir, $profile, $nama);
$pdf->Output('laporan-kas-bank['.date('d-M-Y').'].pdf', 'I');
