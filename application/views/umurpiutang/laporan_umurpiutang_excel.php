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

$sheet->setCellValue('A1', $profile['name']); 
$sheet->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai H1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1:J1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'Laporan Umur Piutang');
$sheet->mergeCells('A2:J2'); // Set Merge Cell pada kolom A1 sampai H1
$sheet->getStyle('A2')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A2:J2')->applyFromArray($style_col);

$sheet->setCellValue('A3', tgl_indo($tglAwal).' s/d '.tgl_indo($tglAkhir));
$sheet->mergeCells('A3:J3'); // Set Merge Cell pada kolom A1 sampai H1
$sheet->getStyle('A3')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A3:J3')->applyFromArray($style_col);

// tabel data
$sheet->setCellValue('A5', "Tanggal"); 
$sheet->setCellValue('B5', "No Invoice"); 
$sheet->setCellValue('C5', "Customer");
$sheet->setCellValue('D5', "Jt. Tempo");
$sheet->setCellValue('E5', "Nilai");
$sheet->setCellValue('F5', "Dibayar"); 
$sheet->setCellValue('G5', "Belum JT"); 
$sheet->setCellValue('H5', "0 - 30 Hari"); 
$sheet->setCellValue('I5', "31 - 60 Hari"); 
$sheet->setCellValue('J5', "> 60 Hari"); 

foreach (range('A','J') as $col) {
    $sheet->getStyle($col.'5')->applyFromArray($style_col_table);
}

date_default_timezone_set('Asia/Jakarta');
$now = date("Y-m-d");
$today = new DateTime();

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

// menampilkan data

if(isset($result))
{
    $total_nilai = 0;
    $total_dibayar = 0;
    $total_belumJT = 0;
    $total_30 = 0;
    $total_60 = 0;
    $total_lebih60 = 0;

    foreach ($result as $row)
    {
      $jtTempo = new DateTime($row['jt_tempo']);
      $interval = $jtTempo->diff($today);
      $days = $interval->days;

      $sisa = $row['nilai'] - $row['dibayar'];

      $hari30 = 0;
      $hari60 = 0;
      $hariLebih60 = 0;

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
      
      // Set value ke Excel
      $sheet->setCellValue('A'.$numrow, date("d/n/Y", strtotime($row['tgl_invoice'])));
      $sheet->setCellValue('B'.$numrow, $row['no_ref']);
      $sheet->setCellValue('C'.$numrow, $row['nama_customer']);
      $sheet->setCellValue('D'.$numrow, !empty($row['jt_tempo']) ? date("d/n/Y", strtotime($row['jt_tempo'])) : '-');
      $sheet->setCellValue('E'.$numrow, $row['nilai'] ?? 0);
      $sheet->setCellValue('F'.$numrow, $row['dibayar'] ?? 0);
      $sheet->setCellValue('G'.$numrow, $belumJT);
      $sheet->setCellValue('H'.$numrow, $hari30);
      $sheet->setCellValue('I'.$numrow, $hari60);
      $sheet->setCellValue('J'.$numrow, $hariLebih60);

      foreach (range('A','J') as $col) {
        $sheet->getStyle($col.$numrow)->applyFromArray($style_row);
      }

      // Total
      $total_nilai += $row['nilai'];
      $total_dibayar += $row['dibayar'];
      $total_belumJT += $belumJT;
      $total_30 += $hari30;
      $total_60 += $hari60;
      $total_lebih60 += $hariLebih60;

      $numrow++;
    }

    // menampilkan total
    $sheet->setCellValue('A'.$numrow, 'Jumlah Total'); 
    $sheet->mergeCells('A'.$numrow.':D'.$numrow);

    $sheet->setCellValue('E'.$numrow, $total_nilai);
    $sheet->setCellValue('F'.$numrow, $total_dibayar);
    $sheet->setCellValue('G'.$numrow, $total_belumJT);
    $sheet->setCellValue('H'.$numrow, $total_30);
    $sheet->setCellValue('I'.$numrow, $total_60);
    $sheet->setCellValue('J'.$numrow, $total_lebih60);

    foreach (range('A','J') as $col) {
        $sheet->getStyle($col.$numrow)->applyFromArray($style_row);
    }

    $sheet->getStyle('A'.$numrow.':J'.$numrow)->getFont()->setBold(true);

} 

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(18);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(40); // wider customer
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(25);
$sheet->getColumnDimension('J')->setWidth(25);

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
// Set judul file excel nya
$sheet->setTitle("Laporan Umur Piutang");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_laporan_umur_piutang'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 



