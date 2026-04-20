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
            $this->cell(90,6,"Laporan Perubahan Ekuitas",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
        }
    }

    function Content($data_akun, $labarugi, $perubahan_ekuitas_data, $bln, $tahun, $tglAkhir, $saldo_awal, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Laporan Perubahan Ekuitas',0,1,'C',1); 
        $this->cell(0,6,'Periode '.getBulan($bln).' '.$tahun,0,1,'C',1); 
        $this->Ln(2);

        $this->SetLineWidth(0.8);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Saldo per '.date("d/n/Y", strtotime($tahun.'/1/1')),0,0,'L',1); 

        $modal_awal = $saldo_awal;
        $total_laba_rugi = $labarugi;

        $this->cell(35,6,'Rp. '.number_format($modal_awal,0,',','.'),0,1,'R',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);
        
        $sum_ekuitas_debit = 0;
        // cek apakah ada data di perubahan ekuitas
        if(isset($perubahan_ekuitas_data[0][310]))
        {
            // jika total laba rugi >= 0, maka jalankan perintah dibawah
            if($total_laba_rugi >= 0) {  
                $this->setFont('Arial','B',10);
                $this->setFillColor(255,255,255);  
                $this->cell(190,6,'Penambahan',0,0,'L',1); 
                $this->Ln();

                $this->SetLineWidth(0.5);
                $this->Line(10,$this->GetY(),200,$this->GetY());
                $this->SetLineWidth(0);

                $this->setFont('Arial','B',10);
                $this->setFillColor(255,255,255);  
                $this->cell(20,6,'',0,0,'L',1); 
                $this->cell(100,6,'Laba Bersih',0,0,'L',1); 
                $this->cell(35,6,'Rp. '.number_format(abs($total_laba_rugi),0,',','.'),0,0,'R',1);
                $this->cell(35,6,'',0,0,'C',1); 
                $this->Ln();
                // Mendapatkan noakun 11 
                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] < 0){ 
                        // Menulis KOde
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                        $sum_ekuitas_debit += abs($row['saldo']); 
                    } 
                }
                // jumlah penambahan ekuitas
                $sum_ekuitas_debit = $sum_ekuitas_debit + abs($total_laba_rugi); 
            } else {
                $this->setFont('Arial','B',10);
                $this->setFillColor(255,255,255);  
                $this->cell(190,6,'Penambahan',0,0,'L',1); 
                $this->Ln();

                $this->SetLineWidth(0.5);
                $this->Line(10,$this->GetY(),200,$this->GetY());
                $this->SetLineWidth(0);

                // Mendapatkan noakun 11 
                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] < 0){ 
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                        $sum_ekuitas_debit += abs($row['saldo']); 
                    }
                }
                // jumlah penambahan ekuitas
                $sum_ekuitas_debit = $sum_ekuitas_debit; 
            }
        }

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Penambahan',0,0,'L',1); 
        $this->cell(35,6,'Rp. '.number_format($sum_ekuitas_debit,0,',','.'),0,1,'R',1); 

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $sum_ekuitas_kredit = 0;
        // Jika terdapat data di $perubahan_ekuitas_data
        if(isset($perubahan_ekuitas_data[0][310]))
        {
            //jika totdal laba rugi kurang dari 0
            if($total_laba_rugi < 0) { 
                $this->setFont('Arial','B',10);
                $this->setFillColor(255,255,255);  
                $this->cell(190,6,'Pengurang',0,1,'L',1); 
                $this->cell(20,6,'',0,1,'L',1);

                $this->SetLineWidth(0.5);
                $this->Line(10,$this->GetY(),200,$this->GetY());
                $this->SetLineWidth(0);
                 
                $this->cell(100,6,'Rugi Bersih',0,0,'L',1); 
                $this->cell(35,6,'Rp. '.number_format(abs($total_laba_rugi),0,',','.'),0,0,'R',1); 
                $this->cell(35,6,'',0,0,'C',1);
                $this->Ln();


                // Menampilkan akun selain modal
                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] > 0){
                        // Menulis KOde
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $this->Ln();
                        $sum_ekuitas_kredit += $row['saldo']; 
                    }
                }
                // jumlah pengurangan ekuitas
                $sum_ekuitas_kredit = $sum_ekuitas_kredit + abs($total_laba_rugi);  
            } else {
                $this->setFont('Arial','B',10);
                $this->setFillColor(255,255,255);  
                $this->cell(190,6,'Pengurang',0,0,'L',1); 
                $this->Ln();

                $this->SetLineWidth(0.5);
                $this->Line(10,$this->GetY(),200,$this->GetY());
                $this->SetLineWidth(0);

                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] > 0){ 
                        // Menulis KOde
                        $this->setFont('Arial','',9);
                        $this->setFillColor(255,255,255);  
                        $this->cell(20,6,$row['noakun'],0,0,'C',1);
                        $this->cell(100,6,$row['nama'],0,0,'L',1);
                        $this->cell(35,6,'Rp. '.number_format(abs($row['saldo']),0,',','.'),0,0,'R',1);
                        $this->cell(35,6,'',0,0,'C',1);
                        $sum_ekuitas_kredit += $row['saldo']; 
                        $this->Ln();
                    }
                }
                // jumlah pengurangan ekuitas
                $sum_ekuitas_kredit = $sum_ekuitas_kredit; 
            }
        }

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(20,6,'',0,0,'L',1); 
        $this->cell(135,6,'Total Pengurangan',0,0,'L',1); 
        $this->cell(35,6,'Rp. '.number_format($sum_ekuitas_kredit,0,',','.'),0,0,'R',1); 
        $this->Ln();

        $this->SetLineWidth(0.5);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->SetLineWidth(0);

        $modal_akhir = $modal_awal + $sum_ekuitas_debit - $sum_ekuitas_kredit;

        $this->setFont('Arial','B',10);
        $this->setFillColor(255,255,255);  
        $this->cell(155,6,'Saldo per '.date("d/n/Y", strtotime($tglAkhir)),0,0,'L',1); 
        $this->cell(35,6,'Rp. '.number_format($modal_akhir,0,',','.'),0,0,'R',1); 
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
$pdf->setTitle('Laporan Perubahan Ekuitas '.$profile['name'],true);
$pdf->Content($data_akun, $labarugi, $perubahan_ekuitas_data, $bln, $tahun, $tglAkhir, $saldo_awal, $profile);
$pdf->Output('laporan-perubahan-ekuitas['.date('d-M-Y').'].pdf', 'I');
