<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

$sheet = $spreadsheet->getActiveSheet();

$style_col = [
  'font' => ['bold' => true], // Set font nya jadi bold
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ],
];

$style_col_table = [
  'font' => ['bold' => true], // Set font nya jadi bold
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ],
  'borders' => [
    'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
    'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
    'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
    'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
  ]
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
$sheet->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1:F1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'Laporan Transaksi Pendapatan'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A2:F2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A2:F2')->applyFromArray($style_col);

$sheet->setCellValue('A3', tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir)); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A3:F3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A3:F3')->applyFromArray($style_col);

// tabel data
$sheet->setCellValue('A5', "Tanggal"); 
$sheet->setCellValue('B5', "No. Tranasaksi"); 
$sheet->setCellValue('C5', "Keterangan"); 
$sheet->setCellValue('D5', "Akun Kas/Bank");
$sheet->setCellValue('E5', "Akun Pendapatan");
$sheet->setCellValue('F5', "Nilai"); 

$sheet->getStyle('A5')->applyFromArray($style_col_table);
$sheet->getStyle('B5')->applyFromArray($style_col_table);
$sheet->getStyle('C5')->applyFromArray($style_col_table);
$sheet->getStyle('D5')->applyFromArray($style_col_table);
$sheet->getStyle('E5')->applyFromArray($style_col_table);
$sheet->getStyle('F5')->applyFromArray($style_col_table);

date_default_timezone_set('Asia/Jakarta');
$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

// menampilkan data
//file_put_contents(APPPATH.'logs/excel_debug.txt', print_r($dbAll, true));
if (!empty($dbAll))
{
    $total_nilai = 0;
   
    foreach ($dbAll as $row)
    {
          $sheet->setCellValue('A'.$numrow, date("d/n/Y", strtotime($row['tgl'])));
          $sheet->setCellValue('B'.$numrow, $row['no_trans_formatted']);
          $sheet->setCellValue('C'.$numrow, $row['keterangan']);
          $sheet->setCellValue('D'.$numrow, $row['nm_kas']);
          $sheet->setCellValue('E'.$numrow, $row['nm_pendapatan']);
          $sheet->setCellValue('F'.$numrow, $row['nilai']);

          $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);

          $total_nilai += $row['nilai'];
          
          $numrow++;
    }

    // menampoilkan total

    $sheet->setCellValue('A'.$numrow, 'Jumlah Total' ); // Set kolom A1 dengan tulisan "DATA SISWA"
    $sheet->mergeCells('A'.$numrow.':E'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
    $sheet->getStyle('A'.$numrow.':E'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
    $sheet->getStyle('A'.$numrow.':E'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

    $sheet->setCellValue('F'.$numrow, $total_nilai);

    $sheet->getStyle('A'.$numrow.':E'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);

} 

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(20); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(50); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(40); // Set width kolom D
$sheet->getColumnDimension('E')->setWidth(40); // Set width kolom D
$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom D


// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
// Set judul file excel nya
$sheet->setTitle("Laporan Transaksi Pendapatan");

// instantiate Xlsx
$writer = new Xlsx($spreadsheet);

$filename = time().'_laporan_transaksi_pendapatan.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

ob_end_clean(); // 🔥 VERY IMPORTANT
flush();

$writer->save('php://output');
exit;
