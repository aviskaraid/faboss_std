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
            $this->cell(90,6,"Daftar Asset",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    
    function Content($result, $profile){
        if ($this->PageNo() == 1){
            // Kop Laporan
            $this->Ln(1);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $this->cell(0,7,$profile['name'],0,1,'C',1); 
            $this->cell(0,7,'Daftar Asset',0,1,'C',1); 

            // Line break
            $this->Ln(2);
            $startX = $this->GetX();
            $startY = $this->GetY();
            $rowHeight = 12; // total height for 2-line header

            // Column 1
            $this->SetXY($startX, $startY);
            $this->MultiCell(30, 12, "Nama Asset", 1, 'C', true);

            // Column 2
            $this->SetXY($startX + 30, $startY);
            $this->MultiCell(25, 12, "Kode Asset", 1, 'C', true);

            // Column 3
            $this->SetXY($startX + 55, $startY);
            $this->MultiCell(25, 12, "Lokasi Asset", 1, 'C', true);

            // Column 4
            $this->SetXY($startX + 80, $startY);
            $this->MultiCell(25, 6, "Tanggal\nPerolehan", 1, 'C', true);

            // Column 5
            $this->SetXY($startX + 105, $startY);
            $this->MultiCell(25, 6, "Akhir Masa\nManfaat", 1, 'C', true);

            // Column 6
            $this->SetXY($startX + 130, $startY);
            $this->MultiCell(30, 6, "Umur\nManfaat (Tahun)", 1, 'C', true);

            // Column 7
            $this->SetXY($startX + 160, $startY);
            $this->MultiCell(30, 6, "Harga\nPerolehan", 1, 'C', true);

            // Move cursor to the next row after header
            $this->SetY($startY + $rowHeight);
        } else {
            $this->Ln(2);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255);
            $startX = $this->GetX();
            $startY = $this->GetY();
            $rowHeight = 12; // total height for 2-line header

            // Column 1
            $this->SetXY($startX, $startY);
            $this->MultiCell(30, 12, "Nama Asset", 1, 'C', true);

            // Column 2
            $this->SetXY($startX + 30, $startY);
            $this->MultiCell(25, 12, "Kode Asset", 1, 'C', true);

            // Column 3
            $this->SetXY($startX + 55, $startY);
            $this->MultiCell(25, 12, "Lokasi Asset", 1, 'C', true);

            // Column 4
            $this->SetXY($startX + 80, $startY);
            $this->MultiCell(25, 6, "Tanggal\nPerolehan", 1, 'C', true);

            // Column 5
            $this->SetXY($startX + 105, $startY);
            $this->MultiCell(25, 6, "Akhir Masa\nManfaat", 1, 'C', true);

            // Column 6
            $this->SetXY($startX + 130, $startY);
            $this->MultiCell(30, 6, "Umur\nManfaat (Tahun)", 1, 'C', true);

            // Column 7
            $this->SetXY($startX + 160, $startY);
            $this->MultiCell(30, 6, "Harga\nPerolehan", 1, 'C', true);

            // Move cursor to the next row after header
            $this->SetY($startY + $rowHeight);
        }

        // menampilkan datanya
        if($result) {
            $this->setFont('Arial','',9);
            $this->SetWidths(Array(30,25,25,25,25,30,30));
            $this->SetAligns(Array('L','L','L','L','L','L','R'));
            $this->SetLineHeight(5); 
            $total_nilai = 0;
            foreach ($result as $row) {       
                $this->Row(Array(
                    $row['nama'],
                    $row['kode'],
                    $row['lokasi'],
                    date("d/n/Y", strtotime($row['tgl'])),
                    convertDbdateToDate(date('Y-m-d', strtotime('-1 days', strtotime('+'.$row['umur'].' months', strtotime($row['tgl']))))),
                    $row['umur']." (". ($row['umur']*12) ." Bulan)",
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
$pdf->setTitle('Daftar Asset '.$profile['name'],true);
$pdf->Content($result, $profile);
$pdf->Output('daftar-asset['.date('d-M-Y').'].pdf', 'I');
