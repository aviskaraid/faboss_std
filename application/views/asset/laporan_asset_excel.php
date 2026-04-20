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
$sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1:G1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'Daftar Asset'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A2:G2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A2:G2')->applyFromArray($style_col);

$sheet->setCellValue('A3', 'Per Tanggal '. tgl_indo($hari_ini)); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A3:G3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A3:G3')->applyFromArray($style_col);

// tabel data
$sheet->setCellValue('A5', "Nama Asset"); 
$sheet->setCellValue('B5', "Kode Asset"); 
$sheet->setCellValue('C5', "Lokasi Asset"); 
$sheet->setCellValue('D5', "Tanggal Perolehan"); 
$sheet->setCellValue('E5', "Akhir Masa Manfaat"); 
$sheet->setCellValue('F5', "Umur Manfaat (Tahun)");
$sheet->setCellValue('G5', "Harga Perolehan"); 

$sheet->getStyle('A5')->applyFromArray($style_col_table);
$sheet->getStyle('B5')->applyFromArray($style_col_table);
$sheet->getStyle('C5')->applyFromArray($style_col_table);
$sheet->getStyle('D5')->applyFromArray($style_col_table);
$sheet->getStyle('E5')->applyFromArray($style_col_table);
$sheet->getStyle('F5')->applyFromArray($style_col_table);
$sheet->getStyle('G5')->applyFromArray($style_col_table);


date_default_timezone_set('Asia/Jakarta');
$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4


// menampilkan data

if(isset($result))
{
    $total_harga = 0;
    foreach ($result as $row)
    {
          $sheet->setCellValue('A'.$numrow, $row['nama']);
          $sheet->setCellValue('B'.$numrow, $row['kode']);
          $sheet->setCellValue('C'.$numrow, $row['lokasi']);
          $sheet->setCellValue('D'.$numrow, convertDbdateToDate($row['tgl']));
          $sheet->setCellValue('E'.$numrow, convertDbdateToDate(date('Y-m-d', strtotime('-1 days', strtotime('+'.$row['umur'].' months', strtotime($row['tgl']))))));
          $sheet->setCellValue('F'.$numrow, $row['umur']." (". ($row['umur']*12) ." Bulan)");
          $sheet->setCellValue('G'.$numrow, $row['nilai']);

          $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);

          $total_harga += $row['nilai'];
          
          $numrow++;
    }

    // menampilkan total

    $sheet->setCellValue('F'.$numrow, 'Jumlah Total' ); // Set kolom A1 dengan tulisan "DATA SISWA"
    $sheet->mergeCells('A'.$numrow.':E'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
    $sheet->getStyle('A'.$numrow.':E'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
    $sheet->getStyle('A'.$numrow.':E'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

    $sheet->setCellValue('G'.$numrow, $total_harga);
    
    $sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
} 



// Set width kolom
$sheet->getColumnDimension('A')->setWidth(30); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(30); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(30); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(30); // Set width kolom D
$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom R
$sheet->getColumnDimension('G')->setWidth(30); // Set width kolom G

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
// Set judul file excel nya
$sheet->setTitle("Daftar Asset");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_daftar_asset'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 



