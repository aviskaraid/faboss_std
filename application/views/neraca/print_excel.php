<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$style_col = [
  'font' => ['bold' => true],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
  ],
];

$style_col_table = [
  'font' => ['bold' => true],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
  ],
  'borders' => [
    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
  ]
];

$style_row = [
  'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
  'borders' => [
    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
  ]
];

/* FORMAT ANGKA AKUNTANSI */
$style_number = [
  'numberFormat' => [
    'formatCode' => '#,##0;(#,##0)'
  ]
];

/* HEADER */
$sheet->setCellValue('A1', $profile['name']);
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1:D1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'LAPORAN NERACA');
$sheet->mergeCells('A2:D2');
$sheet->getStyle('A2:D2')->applyFromArray($style_col);

$sheet->setCellValue('A3', 'Periode '.$periode);
$sheet->mergeCells('A3:D3');
$sheet->getStyle('A3:D3')->applyFromArray($style_col);

$rowNum = 5;

/* JUDUL KOLOM */
$sheet->setCellValue('A'.$rowNum, "No Akun");
$sheet->setCellValue('B'.$rowNum, "Nama Akun");
$sheet->setCellValue('C'.$rowNum, "");
$sheet->setCellValue('D'.$rowNum, "Saldo");
$sheet->getStyle('A'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);

$rowNum++;

/* ================= ASET ================= */
$sheet->setCellValue('A'.$rowNum, 'ASET');
$sheet->mergeCells('A'.$rowNum.':D'.$rowNum);
$sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
$rowNum++;

$total_aset = 0;

foreach($neraca as $group){
    if($group['tipe'] != 'A') continue;

    $sheet->setCellValue('A'.$rowNum, $group['nama_kel_akun']);
    $sheet->mergeCells('A'.$rowNum.':D'.$rowNum);
    $sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
    $rowNum++;

    foreach($group['akun'] as $akun){
        $sheet->setCellValue('A'.$rowNum, $akun['noakun']);
        $sheet->setCellValue('B'.$rowNum, $akun['nama']);
        $sheet->setCellValue('D'.$rowNum, $akun['saldo']);

        $sheet->getStyle('A'.$rowNum.':D'.$rowNum)->applyFromArray($style_row);
        $sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

        $rowNum++;
    }

    $sheet->setCellValue('C'.$rowNum, 'Total '.$group['nama_kel_akun']);
    $sheet->setCellValue('D'.$rowNum, $group['subtotal']);
    $sheet->getStyle('C'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);
    $sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

    $total_aset += $group['subtotal'];
    $rowNum += 2;
}

/* TOTAL ASET */
$sheet->setCellValue('C'.$rowNum, 'TOTAL ASET');
$sheet->setCellValue('D'.$rowNum, $total_aset);
$sheet->getStyle('C'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);
$sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

$rowNum += 3;

/* ================= LIABILITAS & EKUITAS ================= */
$sheet->setCellValue('A'.$rowNum, 'LIABILITAS DAN EKUITAS');
$sheet->mergeCells('A'.$rowNum.':D'.$rowNum);
$sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
$rowNum++;

$total_liabilitas = 0;
$total_ekuitas = 0;

foreach($neraca as $group){
    if($group['tipe'] != 'P') continue;

    $sheet->setCellValue('A'.$rowNum, $group['nama_kel_akun']);
    $sheet->mergeCells('A'.$rowNum.':D'.$rowNum);
    $sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
    $rowNum++;

    foreach($group['akun'] as $akun){
        $sheet->setCellValue('A'.$rowNum, $akun['noakun']);
        $sheet->setCellValue('B'.$rowNum, $akun['nama']);
        $sheet->setCellValue('D'.$rowNum, $akun['saldo']);

        $sheet->getStyle('A'.$rowNum.':D'.$rowNum)->applyFromArray($style_row);
        $sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

        $rowNum++;
    }

    $sheet->setCellValue('C'.$rowNum, 'Total '.$group['nama_kel_akun']);
    $sheet->setCellValue('D'.$rowNum, $group['subtotal']);
    $sheet->getStyle('C'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);
    $sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

    if(stripos($group['nama_kel_akun'],'modal') !== false)
        $total_ekuitas += $group['subtotal'];
    else
        $total_liabilitas += $group['subtotal'];

    $rowNum += 2;
}

/* LABA BERJALAN */
$sheet->setCellValue('C'.$rowNum, 'Laba/Rugi Berjalan');
$sheet->setCellValue('D'.$rowNum, $laba_berjalan);
$sheet->getStyle('C'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);
$sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

$total_ekuitas += $laba_berjalan;
$rowNum++;

/* TOTAL KESELURUHAN */
$sheet->setCellValue('C'.$rowNum, 'TOTAL LIABILITAS & EKUITAS');
$sheet->setCellValue('D'.$rowNum, $total_liabilitas + $total_ekuitas);
$sheet->getStyle('C'.$rowNum.':D'.$rowNum)->applyFromArray($style_col_table);
$sheet->getStyle('D'.$rowNum)->applyFromArray($style_number);

/* WIDTH */
foreach(range('A','D') as $col){
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->setTitle("Laporan Neraca");

$writer = new Xlsx($spreadsheet);
$filename = 'Laporan_Neraca_'.time().'.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean();
flush();
$writer->save('php://output');
exit;
