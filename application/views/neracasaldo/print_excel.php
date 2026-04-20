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
$sheet->mergeCells('A1:H1');
$sheet->getStyle('A1:H1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'LAPORAN NERACA SALDO');
$sheet->mergeCells('A2:H2');
$sheet->getStyle('A2:H2')->applyFromArray($style_col);

$sheet->setCellValue('A3', 'Periode '.$periode);
$sheet->mergeCells('A3:H3');
$sheet->getStyle('A3:H3')->applyFromArray($style_col);

$rowNum = 5;

/* JUDUL KOLOM */
$sheet->setCellValue('B'.$rowNum, "Nama Akun");
$sheet->setCellValue('C'.$rowNum, "Saldo Awal");
$sheet->setCellValue('E'.$rowNum, "Mutasi");
$sheet->setCellValue('G'.$rowNum, "Saldo Akhir");

// Merge header
$sheet->mergeCells('A'.$rowNum.':A'.($rowNum+1));
$sheet->mergeCells('B'.$rowNum.':B'.($rowNum+1));
$sheet->mergeCells('C'.$rowNum.':D'.$rowNum);
$sheet->mergeCells('E'.$rowNum.':F'.$rowNum);
$sheet->mergeCells('G'.$rowNum.':H'.$rowNum);

// Sub header
$rowNum++;

$sheet->setCellValue('C'.$rowNum, "Debit");
$sheet->setCellValue('D'.$rowNum, "Kredit");
$sheet->setCellValue('E'.$rowNum, "Debit");
$sheet->setCellValue('F'.$rowNum, "Kredit");
$sheet->setCellValue('G'.$rowNum, "Debit");
$sheet->setCellValue('H'.$rowNum, "Kredit");

// Apply style
$sheet->getStyle('A'.($rowNum-1).':H'.$rowNum)->applyFromArray($style_col_table);

$rowNum++;

$total_saldo_awal_debit = 0;
$total_saldo_awal_credit = 0;
$total_mutasi_debit = 0;
$total_mutasi_credit = 0;
$total_saldo_akhir_debit = 0;  
$total_saldo_akhir_credit = 0;

foreach($neracasaldo as $n){

    $sa_debit  = $n['saldo_awal_debit'] ?? 0;
    $sa_kredit = $n['saldo_awal_credit'] ?? 0;
    $mu_debit  = $n['mutasi_debit'] ?? 0;
    $mu_kredit = $n['mutasi_credit'] ?? 0;
    $ak_debit  = $n['saldo_akhir_debit'] ?? 0;
    $ak_kredit = $n['saldo_akhir_credit'] ?? 0;

    // Isi data
    $sheet->setCellValue('A'.$rowNum, $n['noakun']);
    $sheet->setCellValue('B'.$rowNum, $n['nama']);

    $sheet->setCellValue('C'.$rowNum, $sa_debit ?: '');
    $sheet->setCellValue('D'.$rowNum, $sa_kredit ?: '');
    $sheet->setCellValue('E'.$rowNum, $mu_debit ?: '');
    $sheet->setCellValue('F'.$rowNum, $mu_kredit ?: '');
    $sheet->setCellValue('G'.$rowNum, $ak_debit ?: '');
    $sheet->setCellValue('H'.$rowNum, $ak_kredit ?: '');

    // Style row
    $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->applyFromArray($style_row);

    // Format angka
    $sheet->getStyle('C'.$rowNum.':H'.$rowNum)->applyFromArray($style_number);

    // Alignment kanan untuk angka
    $sheet->getStyle('C'.$rowNum.':H'.$rowNum)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // Akumulasi total
    $total_saldo_awal_debit  += $sa_debit;
    $total_saldo_awal_credit += $sa_kredit;
    $total_mutasi_debit      += $mu_debit;
    $total_mutasi_credit     += $mu_kredit;
    $total_saldo_akhir_debit += $ak_debit;
    $total_saldo_akhir_credit+= $ak_kredit;

    $rowNum++;
}

// TOTAL
$sheet->setCellValue('A'.$rowNum, 'TOTAL');
$sheet->mergeCells('A'.$rowNum.':B'.$rowNum);

$sheet->setCellValue('C'.$rowNum, $total_saldo_awal_debit);
$sheet->setCellValue('D'.$rowNum, $total_saldo_awal_credit);
$sheet->setCellValue('E'.$rowNum, $total_mutasi_debit);
$sheet->setCellValue('F'.$rowNum, $total_mutasi_credit);
$sheet->setCellValue('G'.$rowNum, $total_saldo_akhir_debit);
$sheet->setCellValue('H'.$rowNum, $total_saldo_akhir_credit);

// Style total
$sheet->getStyle('A'.$rowNum.':H'.$rowNum)->applyFromArray($style_col_table);
$sheet->getStyle('C'.$rowNum.':H'.$rowNum)->applyFromArray($style_number);

$sheet->getStyle('C'.$rowNum.':H'.$rowNum)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

foreach(range('A','H') as $col){
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->setTitle("Laporan Neraca Saldo");

$writer = new Xlsx($spreadsheet);
$filename = 'Laporan_Neraca_Saldo_'.time().'.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean();
flush();
$writer->save('php://output');
exit;
