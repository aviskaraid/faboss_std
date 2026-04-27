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
            $this->cell(90,6,"Buku Besar",0,0,'L',1); 
            $this->cell(100,6,"Printed date : " . date('d-M-Y'),0,1,'R',1); 
            $this->Ln(3);
        }
    }
 
    function Content($journal_data, $account_data, $tglAwal, $tglAkhir, $profile){
        // Kop Laporan
        $this->Ln(3);
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,$profile['name'],0,1,'C',1); 
        $this->cell(0,6,'Buku Besar',0,1,'C',1); 
        $this->cell(0,6,date("d/n/Y", strtotime($tglAwal)).' s/d '.date("d/n/Y", strtotime($tglAkhir)),0,1,'C',1); 

        // Line break
        $this->Ln(3);
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(18,6,'','LTR',0,'C',1);
        $this->cell(28,6,'','LTR',0,'C',1);
        $this->cell(38,6,'','LTR',0,'C',1);
        $this->cell(27,6,'','LTR',0,'C',1);
        $this->cell(27,6,'','LTR',0,'C',1);
        $this->cell(54,6,'Saldo Akhir',1,1,'C',1);

        $this->cell(18,6,'Tanggal','LBR',0,'C',1);
        $this->cell(28,6,'No. Transaksi','LBR',0,'C',1);
        $this->cell(38,6,'Keterangan','LBR',0,'C',1);
        $this->cell(27,6,'Debit','LBR',0,'C',1);
        $this->cell(27,6,'Kredit','LBR',0,'C',1);

        $this->cell(27,6,'Debit',1,0,'C',1);
        $this->cell(27,6,'Kredit',1,0,'C',1);

        $this->Ln();
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255); 
        $this->cell(192,6,'['.$account_data['noakun'].'] '. $account_data['nama'],1,0,'L',1);  

        $totalDebit = 0;
        $totalKredit = 0;
        $sum = 0;

        if ($account_data) {
            $sum = intval($account_data['saldo_awal']);

            $this->Ln();
            $this->setFont('Arial','',9);
            $this->setFillColor(255,255,255);   
            $this->cell(18,6,'',1,0,'L',1);
            $this->cell(28,6,'',1,0,'C',1);
            $this->cell(38,6,'Saldo Awal',1,0,'L',1);
            if($account_data['id_perkiraan'] == 1){
                $this->cell(27,6,'Rp. '.number_format($sum,0,',','.'),1,0,'R',1);
                $this->cell(27,6,'',1,0,'L',1);
                $totalDebit += $sum;
            } else {
                $this->cell(27,6,'',1,0,'C',1);
                $this->cell(27,6,'Rp. '.number_format($sum,0,',','.'),1,0,'R',1);
                $totalKredit += $sum;
            }

            if($account_data['id_perkiraan'] == 1) {
                $this->cell(27,6,'Rp. '.number_format($sum,0,',','.'),1,0,'R',1);
                $this->cell(27,6,'',1,0,'L',1);
            } else {
                $sum = $sum*(-1);
                $this->cell(27,6,'',1,0,'L',1);
                $this->cell(27,6,'Rp. '.number_format(abs($sum),0,',','.'),1,0,'R',1);
            }

            $this->Ln();
            $this->setFont('Arial','',9);
            $this->SetWidths(Array(18,28,38,27,27,27,27));
            $this->SetAligns(Array('C','C','L','R','R','R','R'));
            $this->SetLineHeight(5);
            foreach ($journal_data as $data) {

                if($data['id_perkiraan'] == 1) {
                    
                    $sum += intval($data['nilai']);
                    $totalDebit += intval($data['nilai']);

                    if($sum >= 0){
                        $this->Row(Array(
                            date("d/n/Y", strtotime($data['tgl'])),
                            $data['no_trans_format'],
                            $data['keterangan'],
                            'Rp. '.number_format($data['nilai'],0,',','.'),
                            '',
                            'Rp. '.number_format($sum,0,',','.'),
                            '',
                        ));
                    } else {
                        $this->Row(Array(
                            date("d/n/Y", strtotime($data['tgl'])),
                            $data['no_trans_format'],
                            $data['keterangan'],
                            'Rp. '.number_format($data['nilai'],0,',','.'),
                            '',
                            '',
                            'Rp. '.number_format(abs($sum),0,',','.'),
                        ));
                    }
                } else {

                    $sum -= intval($data['nilai']);
                    $totalKredit += intval($data['nilai']);
                    if($sum >= 0){
                        $this->Row(Array(
                            date("d/n/Y", strtotime($data['tgl'])),
                            $data['no_trans_format'],
                            $data['keterangan'],
                            '',
                            'Rp. '.number_format($data['nilai'],0,',','.'),
                            'Rp. '.number_format($sum,0,',','.'),
                            '',
                        ));
                    } else {
                        $this->Row(Array(
                            date("d/n/Y", strtotime($data['tgl'])),
                            $data['no_trans_format'],
                            $data['keterangan'],
                            '',
                            'Rp. '.number_format($data['nilai'],0,',','.'),
                            '',
                            'Rp. '.number_format(abs($sum),0,',','.'),
                        ));
                    }
                }
            }

            $this->setFont('Arial','B',8);
            $this->setFillColor(255,255,255);   
            $this->cell(84,6,'['.$account_data['noakun'].'] '.$account_data['nama'].' | Saldo Akhir',1,0,'R',1);
            $this->setFont('Arial','B',9);
            $this->setFillColor(255,255,255); 
            $this->cell(27,6,'Rp. '.number_format($totalDebit,0,',','.'),1,0,'R',1);
            $this->cell(27,6,'Rp. '.number_format($totalKredit,0,',','.'),1,0,'R',1);
            if($account_data['id_perkiraan'] == 1){
                if($sum >= 0) {
                    $this->cell(27,6,'Rp. '.number_format($sum,0,',','.'),1,0,'R',1);
                    $this->cell(27,6,'',1,0,'L',1);
                } else {
                    $this->cell(27,6,'',1,0,'L',1);
                    $this->cell(27,6,'Rp. '.number_format(abs($sum),0,',','.'),1,0,'R',1);
                }
            } else {
                if($sum <= 0) {
                    $this->cell(27,6,'',1,0,'L',1);
                    $this->cell(27,6,'Rp. '.number_format(abs($sum),0,',','.'),1,0,'R',1);
                } else {
                    $this->cell(27,6,'Rp. '.number_format($sum,0,',','.'),1,0,'R',1);
                    $this->cell(27,6,'',1,0,'L',1);
                }
            }
        } else {
            $this->setFont('Arial','',9);
            $this->setFillColor(255,255,255);   
            $this->cell(200,6,'Tidak ada data',1,0,'L',1);
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
        $this->Cell(0,10,'Copyright@'.date('Y'),0,0,'L');
        //nomor halaman
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }
}
 
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle('['.$account_data['nama'].']Buku Besar '.$profile['name'],true);
$pdf->Content($journal_data, $account_data, $tglAwal, $tglAkhir, $profile);
$pdf->Output('['.$account_data['nama'].']buku-besar'.date('d-M-Y').'.pdf', 'I');
