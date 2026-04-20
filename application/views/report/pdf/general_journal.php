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
            $this->cell(90,6,"Jurnal Umum",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
            $this->Ln(3);
        }
    }
 
    function Content($journal, $tglAwal, $tglAkhir, $profile){
        if ($this->PageNo() == 1){
            // Kop Laporan
            $this->Ln(3);
            $this->setFont('Arial','B',12);
            $this->setFillColor(255,255,255);
            $this->cell(0,6,$profile['name'],0,1,'C',1); 
            $this->cell(0,6,'Jurnal Umum',0,1,'C',1); 
            $this->cell(0,6,date("d/n/Y", strtotime($tglAwal)).' s/d '.date("d/n/Y", strtotime($tglAkhir)),0,1,'C',1); 

            // Line break
            $this->Ln(3);
            $this->setFont('Arial','B',7);
            $this->setFillColor(255,255,255);
            $this->cell(20,6,'Tanggal',1,0,'C',1);
            $this->cell(25,6,'No Transaksi',1,0,'C',1);
            $this->cell(15,6,'Ref',1,0,'C',1);
            $this->cell(66,6,'Keterangan',1,0,'C',1);
            $this->cell(32,6,'Debit',1,0,'C',1);
            $this->cell(32,6,'Kredit',1,1,'C',1);

        } else {
            $this->Ln(2);
            $this->setFont('Arial','B',7);
            $this->setFillColor(255,255,255);
            $this->cell(20,6,'Tanggal',1,0,'C',1);
            $this->cell(25,6,'No Transaksi',1,0,'C',1);
            $this->cell(15,6,'Ref',1,0,'C',1);
            $this->cell(66,6,'Keterangan',1,0,'C',1);
            $this->cell(32,6,'Debit',1,0,'C',1);
            $this->cell(32,6,'Kredit',1,1,'C',1);
        }

        $totalDebit = 0;
        $totalKredit = 0;
        if($journal) {
            foreach ($journal as $row) {

                if($row['id_perkiraan'] == '1') {
                    $totalDebit += intval($row['nilai']);
                    $this->setFont('Arial','',7);
                    $this->setFillColor(255,255,255);   
                    $this->cell(20,6,date("d/n/Y", strtotime($row['tgl'])),1,0,'C',1);
                    $this->cell(25,6,$row['no_trans'],1,0,'L',1);
                    $this->cell(15,6,$row['noakun'],1,0,'C',1);
                    $this->cell(66,6,'(D) '.$row['nama'],1,0,'L',1);
                    $this->cell(32,6,'Rp. '.number_format($row['nilai'],0,',','.'),1,0,'R',1);
                    $this->cell(32,6,'',1,1,'C',1);
                } else {
                    $totalKredit += intval($row['nilai']);
                    $this->setFont('Arial','',7);
                    $this->setFillColor(255,255,255);   
                    $this->cell(20,6,date("d/n/Y", strtotime($row['tgl'])),1,0,'C',1);
                    $this->cell(25,6,$row['no_trans'],1,0,'L',1);
                    $this->cell(15,6,$row['noakun'],1,0,'C',1);
                    $this->cell(66,6,'(K) '.$row['nama'],1,0,'L',1);
                    $this->cell(32,6,'',1,0,'C',1);
                    $this->cell(32,6,'Rp. '.number_format($row['nilai'],0,',','.'),1,1,'R',1);
                }
            }

            // $this->Ln();
            $this->setFont('Arial','B',7);
            $this->setFillColor(255,255,255);   
            $this->cell(126,6,'Jumlah Total',1,0,'R',1);
            $this->cell(32,6,'Rp. '.number_format($totalDebit,0,',','.'),1,0,'R',1);
            $this->cell(32,6,'Rp. '.number_format($totalKredit,0,',','.'),1,0,'R',1);
                
        } else {
            $this->setFont('Arial','',7);
            $this->setFillColor(255,255,255);   
            $this->cell(190,6,'Tidak ada data',1,0,'L',1);
        };

    }
 
    // Page footer
    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        //buat garis horizontal
        $this->Line(10,$this->GetY(),200,$this->GetY());
        //Arial italic 9
        $this->SetFont('Arial','I',9);
        $this->Cell(0,10,'Copyright@'.date('Y').' PT. ABG',0,0,'L');
        //nomor halaman
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }
}
 
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle('Jurnal Umum '.$profile['name'],true);
$pdf->Content($journal, $tglAwal, $tglAkhir, $profile);
$pdf->Output('jurnal-umum['.date('d-M-Y').'].pdf', 'I');
