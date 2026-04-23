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
            $this->Cell(140,6,"Laporan Piutang",0,0,'L'); 
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
            $this->Cell(0,6,'Daftar Hutang',0,1,'C'); 
            $this->Cell(0,6, tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir),0,1,'C'); 
            $this->Ln(3);
        }

        // FIXED WIDTHS (TOTAL = 277 mm)
        $widths = [20,40,40,20,72,25,25,35];

        $header = [
            'Tanggal',
            'No Invoice',
            'Supplier',
            'Jt. Tempo',
            'Keterangan',
            'Nominal',
            'Dibayar',
            'Status'
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
            $this->SetAligns(['C','L','L','C','L','R','R','R']);
            $this->SetLineHeight(5);

            $total_nilai = 0;
            $total_dibayar = 0;

            foreach ($result as $row) {
                $this->Row([
                    date("d/n/Y", strtotime($row['tgl'])),
                    $row['no_ref'],
                    $row['nama_supplier'],
                    $row['jt_tempo'] ? date("d/n/Y", strtotime($row['jt_tempo'])) : '-',
                    $this->SmartWrapText($row['deskripsi'], $widths[4] - 2),
                    'Rp. '.number_format($row['nilai'],0,',','.'),
                    'Rp. '.number_format($row['dibayar'],0,',','.'),
                    ($row['dibayar'] < $row['nilai']) ? "Belum Lunas" : "Sudah Lunas",
                ]);

                $total_nilai += $row['nilai'];
                $total_dibayar += $row['dibayar'];
            }

            // TOTAL ROW
            $this->SetFont('Arial','B',9);

            $labelWidth = $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4];

            $this->Cell($labelWidth,6,'Jumlah Total',1,0,'R');
            $this->Cell($widths[5],6,'Rp. '.number_format($total_nilai,0,',','.'),1,0,'R');
            $this->Cell($widths[6],6,'Rp. '.number_format($total_dibayar,0,',','.'),1,0,'R');
            $this->Cell($widths[7],6,'',1,1,'R');

        } else {
            $this->Cell(277,6,'Tidak ada data',1,1,'L');
        }
    }
}

/* ================= EXECUTION ================= */
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Landscape

$pdf->SetTitle('Laporan Hutang '.$profile['name'], true);
$pdf->Content($result, $tglAwal, $tglAkhir, $profile);

$pdf->Output('laporan-hutang['.date('d-M-Y').'].pdf', 'I');