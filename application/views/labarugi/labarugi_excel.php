<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/* ================= STYLE ================= */
$boldCenter = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
];

$boldLeft = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
];

$rowStyle = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

function rp($sheet,$cell){
    $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0;(#,##0)');
}

/* ================= HEADER ================= */
$sheet->setCellValue('A1', $profile['name']);
$sheet->mergeCells('A1:B1');
$sheet->getStyle('A1')->applyFromArray($boldCenter);

$sheet->setCellValue('A2', 'LAPORAN LABA RUGI');
$sheet->mergeCells('A2:B2');
$sheet->getStyle('A2')->applyFromArray($boldCenter);

$sheet->setCellValue('A3', 'Periode '.$periode);
$sheet->mergeCells('A3:B3');
$sheet->getStyle('A3')->applyFromArray($boldCenter);

$rowNum = 5;

/* ================= PENDAPATAN ================= */
$sheet->setCellValue('A'.$rowNum, 'PENDAPATAN');
$sheet->mergeCells("A{$rowNum}:B{$rowNum}");
$sheet->getStyle("A{$rowNum}")->applyFromArray($boldLeft);
$rowNum++;

$total_pendapatan = 0;

foreach($pendapatan as $group){
    $sheet->setCellValue('A'.$rowNum, $group['nama_kel_akun']);
    $sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
    $rowNum++;

    foreach($group['akun'] as $a){
        $sheet->setCellValue('A'.$rowNum, '   '.$a['noakun'].' '.$a['nama']);
        $sheet->setCellValue('B'.$rowNum, $a['saldo']);
        rp($sheet,'B'.$rowNum);
        $sheet->getStyle("A{$rowNum}:B{$rowNum}")->applyFromArray($rowStyle);
        $rowNum++;
    }

    $sheet->setCellValue('A'.$rowNum, 'Total '.$group['nama_kel_akun']);
    $sheet->setCellValue('B'.$rowNum, $group['subtotal']);
    rp($sheet,'B'.$rowNum);
    $sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);
    $sheet->getStyle("A{$rowNum}:B{$rowNum}")->applyFromArray($rowStyle);
    $rowNum += 2;

    $total_pendapatan += $group['subtotal'];
}

$sheet->setCellValue('A'.$rowNum, 'TOTAL PENDAPATAN');
$sheet->setCellValue('B'.$rowNum, $total_pendapatan);
rp($sheet,'B'.$rowNum);
$sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);
$rowNum += 3;

/* ================= HPP ================= */
$total_hpp = 0;

foreach($hpp as $group){
    $sheet->setCellValue('A'.$rowNum, 'HARGA POKOK PENJUALAN');
    $sheet->getStyle('A'.$rowNum)->applyFromArray($boldLeft);
    $rowNum++;

    foreach($group['akun'] as $a){
        $sheet->setCellValue('A'.$rowNum, '   '.$a['noakun'].' '.$a['nama']);
        $sheet->setCellValue('B'.$rowNum, $a['saldo']);
        rp($sheet,'B'.$rowNum);
        $sheet->getStyle("A{$rowNum}:B{$rowNum}")->applyFromArray($rowStyle);
        $rowNum++;
    }

    $sheet->setCellValue('A'.$rowNum, 'TOTAL HPP');
    $sheet->setCellValue('B'.$rowNum, $group['subtotal']);
    rp($sheet,'B'.$rowNum);
    $sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);
    $rowNum += 2;

    $total_hpp += abs($group['subtotal']);
}

/* ================= LABA KOTOR ================= */
$laba_kotor = $total_pendapatan - $total_hpp;

$sheet->setCellValue('A'.$rowNum, 'LABA / RUGI KOTOR');
$sheet->setCellValue('B'.$rowNum, $laba_kotor);
rp($sheet,'B'.$rowNum);
$sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);
$rowNum += 3;

/* ================= BEBAN ================= */
$total_beban = 0;

foreach($beban as $group){
    $sheet->setCellValue('A'.$rowNum, $group['nama_kel_akun']);
    $sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
    $rowNum++;

    foreach($group['akun'] as $a){
        $sheet->setCellValue('A'.$rowNum, '   '.$a['noakun'].' '.$a['nama']);
        $sheet->setCellValue('B'.$rowNum, $a['saldo']);
        rp($sheet,'B'.$rowNum);
        $sheet->getStyle("A{$rowNum}:B{$rowNum}")->applyFromArray($rowStyle);
        $rowNum++;
    }

    $sheet->setCellValue('A'.$rowNum, 'Total '.$group['nama_kel_akun']);
    $sheet->setCellValue('B'.$rowNum, $group['subtotal']);
    rp($sheet,'B'.$rowNum);
    $sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);
    $rowNum += 2;

    $total_beban += abs($group['subtotal']);
}

/* ================= LABA BERSIH ================= */
$laba_bersih = $laba_kotor - $total_beban;

$sheet->setCellValue('A'.$rowNum, 'LABA / RUGI BERSIH');
$sheet->setCellValue('B'.$rowNum, $laba_bersih);
rp($sheet,'B'.$rowNum);
$sheet->getStyle("A{$rowNum}:B{$rowNum}")->getFont()->setBold(true);

/* ================= WIDTH ================= */
$sheet->getColumnDimension('A')->setWidth(60);
$sheet->getColumnDimension('B')->setWidth(25);

$writer = new Xlsx($spreadsheet);
$filename = 'Laporan_Laba_Rugi_'.$bulan.'_'.$tahun.'.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
