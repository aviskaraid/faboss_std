<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/PDF_MC_Table.php');

class PDF extends PDF_MC_Table{

    /* ================= HEADER ================= */
    function Header(){
        $this->SetFont('Arial','I',9);

        if ($this->PageNo() == 1){
            $this->Cell(140,6,'',0,0,'L'); 
            $this->Cell(140,6,"Printed date : " . date('d-M-Y'),0,1,'R'); 
        } else {
            $this->Cell(140,6,"Laporan Umur Piutang",0,0,'L'); 
            $this->Cell(140,6,"Printed date : " . date('d-M-Y'),0,1,'R'); 
        }

        $this->Line(10,$this->GetY(),287,$this->GetY());
    }

    /* ================= FOOTER ================= */
    function Footer(){
        $this->SetY(-15);
        $this->Line(10,$this->GetY(),287,$this->GetY());
        $this->SetFont('Arial','I',9);
        $this->Cell(0,10,'Copyright@'.date('Y'),0,0,'L');
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }

    /* ================= SMART WRAP ================= */
    function SmartWrapText($text, $maxWidth){
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = ($currentLine === '') ? $word : $currentLine.' '.$word;

            if ($this->GetStringWidth($testLine) < $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine === '') {
                    $lines[] = $this->BreakLongWord($word, $maxWidth);
                } else {
                    $lines[] = $currentLine;
                    $currentLine = $word;
                }
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return implode("\n", $lines);
    }

    function BreakLongWord($word, $maxWidth){
        $result = '';
        $tmp = '';

        for ($i = 0; $i < strlen($word); $i++) {
            $char = $word[$i];
            $test = $tmp . $char;

            if ($this->GetStringWidth($test) < $maxWidth) {
                $tmp = $test;
            } else {
                $result .= $tmp . "\n";
                $tmp = $char;
            }
        }

        if ($tmp !== '') {
            $result .= $tmp;
        }

        return $result;
    }

    /* ================= CONTENT ================= */
    function Content($result, $tglAwal, $tglAkhir, $profile){

        // Title Section
        if ($this->PageNo() == 1){
            $this->Ln(2);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,6,$profile['name'],0,1,'C'); 
            $this->Cell(0,6,'Laporan Umur Piutang',0,1,'C'); 
            $this->Cell(0,6, tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir),0,1,'C'); 
            $this->Ln(3);
        }

        // FIXED WIDTHS (TOTAL = 277 mm)
        $widths = [18,35,50,20,25,25,26,26,26,26];

        $header = [
            'Tanggal',
            'No Invoice',
            'Customer',
            'Jt. Tempo',
            'Nominal',
            'Dibayar',
            'Belum JT',
            '0-30',
            '31-60',
            '>60'
        ];

        // Header Table
        $this->SetFont('Arial','B',9);
        foreach ($header as $i => $col) {
            $this->Cell($widths[$i],6,$col,1,0,'C');
        }
        $this->Ln();

        if ($result) {
            $this->SetFont('Arial','',9);
            $this->SetWidths($widths);
            $this->SetAligns(['C','L','L','C','R','R','R','R','R','R']);
            $this->SetLineHeight(5);

            $total_nilai = 0;
            $total_dibayar = 0;
            $total_belumJT = 0;
            $total_30 = 0;
            $total_60 = 0;
            $total_lebih60 = 0;

            foreach ($result as $row) {
                date_default_timezone_set('Asia/Jakarta');
                $today = new DateTime();

                $jtTempo = !empty($row['jt_tempo']) ? new DateTime($row['jt_tempo']) : null;

                $sisa = ($row['nilai'] ?? 0) - ($row['dibayar'] ?? 0);

                $belumJT = 0;
                $hari30 = 0;
                $hari60 = 0;
                $hariLebih60 = 0;

                if ($jtTempo) {
                    $interval = $today->diff($jtTempo); // IMPORTANT: today -> jtTempo
                    $days = $interval->days;

                    if ($interval->invert == 0) {
                        // FUTURE (jt_tempo > today)
                        $belumJT = $sisa;
                    } else {
                        // OVERDUE
                        if ($days <= 30) {
                            $hari30 = $sisa;
                        } elseif ($days <= 60) {
                            $hari60 = $sisa;
                        } else {
                            $hariLebih60 = $sisa;
                        }
                    }
                } else {
                    // no due date → treat as belum jatuh tempo
                    $belumJT = $sisa;
                }

                $this->Row([
                    date("d/n/Y", strtotime($row['tgl_invoice'] ?? '')),
                    $row['no_ref'] ?? '',
                    $row['nama_customer'] ?? '',
                    !empty($row['jt_tempo']) ? date("d/n/Y", strtotime($row['jt_tempo'])) : '-',
                    'Rp. '.number_format($row['nilai'] ?? 0,0,',','.'),
                    'Rp. '.number_format($row['dibayar'] ?? 0,0,',','.'),
                    'Rp. '.number_format($belumJT,0,',','.'),
                    'Rp. '.number_format($hari30,0,',','.'),
                    'Rp. '.number_format($hari60,0,',','.'),
                    'Rp. '.number_format($hariLebih60,0,',','.'),
                ]);

                $total_nilai += $row['nilai'];
                $total_dibayar += $row['dibayar'];
                $total_belumJT += $belumJT;
                $total_30 += $hari30;
                $total_60 += $hari60;
                $total_lebih60 += $hariLebih60;
            }

            // TOTAL ROW
            $this->SetFont('Arial','B',9);

            $labelWidth = $widths[0] + $widths[1] + $widths[2] + $widths[3];

            $this->Cell($labelWidth,6,'Jumlah Total',1,0,'R');
            $this->Cell($widths[4],6,'Rp. '.number_format($total_nilai,0,',','.'),1,0,'R');
            $this->Cell($widths[5],6,'Rp. '.number_format($total_dibayar,0,',','.'),1,0,'R');
            $this->Cell($widths[6],6,'Rp. '.number_format($total_belumJT,0,',','.'),1,0,'R');
            $this->Cell($widths[7],6,'Rp. '.number_format($total_30,0,',','.'),1,0,'R');
            $this->Cell($widths[8],6,'Rp. '.number_format($total_60,0,',','.'),1,0,'R');
            $this->Cell($widths[9],6,'Rp. '.number_format($total_lebih60,0,',','.'),1,1,'R');
        } else {
            $this->Cell(277,6,'Tidak ada data',1,1,'L');
        }
    }
}

/* ================= EXECUTION ================= */
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Landscape

$pdf->SetTitle('Laporan Umur Piutang '.$profile['name'], true);
$pdf->Content($result, $tglAwal, $tglAkhir, $profile);

$pdf->Output('laporan-umur-piutang['.date('d-M-Y').'].pdf', 'I');