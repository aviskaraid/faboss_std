<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;


$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

$sheet = $spreadsheet->getActiveSheet();

$style_col = [
  'font' => ['bold' => true], // Set font nya jadi bold
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ],
  // 'borders' => [
  //   'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
  //   'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
  //   'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
  //   'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
  // ]
];
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = [
  'alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ],
  'borders' => [
    'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
    'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
    'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
    'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
  ]
];

$sheet->setCellValue('A1', $profile['name']); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A1')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->getStyle('A1:D1')->applyFromArray($style_col);


$sheet->setCellValue('A2', "Neraca Saldo" ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A2:D2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->getStyle('A2:D2')->applyFromArray($style_col);


$sheet->setCellValue('A3', 'Periode '.getBulan($bln).' '.$tahun ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A3:D3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->getStyle('A3:D3')->applyFromArray($style_col);


// Buat header tabel nya pada baris ke 3
$sheet->setCellValue('A5', "No Akun"); 
$sheet->setCellValue('B5', "Nama Akun"); 
$sheet->setCellValue('C5', "Debit"); 
$sheet->setCellValue('D5', "Kredit");

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('A5')->applyFromArray($style_col);
$sheet->getStyle('B5')->applyFromArray($style_col);
$sheet->getStyle('C5')->applyFromArray($style_col);
$sheet->getStyle('D5')->applyFromArray($style_col);

date_default_timezone_set('Asia/Makassar');
$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

$sum_debit = 0;
$sum_kredit = 0;
foreach($kel_akun as $row_akun) {
  if(isset($neraca_saldo_data[0][$row_akun]))
  {
      foreach ($neraca_saldo_data[0][$row_akun] as $key => $row)
      {
        
            $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
            $sheet->setCellValue('B'.$numrow, $row['nama']);

            if($row['saldo'] >= 0) {
              $sheet->setCellValue('C'.$numrow, $row['saldo']);
              $sheet->setCellValue('D'.$numrow, '');

              $sum_debit += $row['saldo']; 
            } else {
              $sheet->setCellValue('C'.$numrow, '');
              $sheet->setCellValue('D'.$numrow, abs($row['saldo']));

              $sum_kredit += $row['saldo'];  
            }

            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

            $numrow++;
        
      }
  }
}


$sheet->setCellValue('A'.$numrow, 'Jumlah Total' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, $sum_debit);
$sheet->setCellValue('D'.$numrow, abs($sum_kredit));


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(20); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
// $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

// Set judul file excel nya
$sheet->setTitle("Neraca Saldo");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_neracasaldo'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 