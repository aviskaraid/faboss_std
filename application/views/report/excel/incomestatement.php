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


$sheet->setCellValue('A2', "Laporan Laba Rugi" ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A2:D2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->getStyle('A2:D2')->applyFromArray($style_col);


$sheet->setCellValue('A3', 'Periode '.getBulan($bln).' '.$tahun ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A3:D3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->getStyle('A3:D3')->applyFromArray($style_col);





// penjualan lokal
$sheet->setCellValue('A5', 'Pendapatan' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A5:D5'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A5:D5')->applyFromArray($style_row);







$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

$sum_penjualan_debit = 0;
$sum_penjualan_kredit = 0;
if(isset($laba_rugi_data[0][410]))
{
    $berhasil_ditampilkan_penjualan = false;

    foreach ($laba_rugi_data[0][410] as $key => $row)
    {
        $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
        $sheet->setCellValue('B'.$numrow, $row['nama']);

        if($row['saldo'] > 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');

          $sum_penjualan_debit += $row['saldo'];
        } else if($row['saldo'] <= 0) { 
          $sheet->setCellValue('C'.$numrow, abs($row['saldo']));
          $sheet->setCellValue('D'.$numrow, '');

          $sum_penjualan_kredit += abs($row['saldo']);
        }

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }
}

$total_pendapatan = $sum_penjualan_kredit - $sum_penjualan_debit;

$sheet->setCellValue('A'.$numrow, 'Total Penjualan' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
$sheet->setCellValue('D'.$numrow, $total_pendapatan);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup penjualan


// hpp
$sum_hpp_debit = 0;
$sum_hpp_kredit = 0;
if(isset($laba_rugi_data[0][510]))
{
  foreach ($laba_rugi_data[0][510] as $key => $row)
  {
    $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
    $sheet->setCellValue('B'.$numrow, $row['nama']);
    if($row['saldo'] >= 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
      $sum_hpp_debit += $row['saldo'];
    } else if($row['saldo'] < 0) { 
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
      $sum_hpp_kredit += abs($row['saldo']);
    }

    $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
  }
}

$total_hpp = $sum_hpp_debit - $sum_hpp_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Harga Pokok Penjualan' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
$sheet->setCellValue('D'.$numrow, $total_hpp);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup hpp


// laba kotor
$laba_kotor = $total_pendapatan - $total_hpp;

$sheet->setCellValue('A'.$numrow, 'Laba (Rugi) Bruto (Gross Profit)' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->setCellValue('D'.$numrow, $laba_kotor);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup laba kotor


// beban operasional
$sum_beban_operasional_debit = 0;
$sum_beban_operasional_kredit = 0;
if(isset($laba_rugi_data[0][610]))
{
  foreach ($laba_rugi_data[0][610] as $key => $row)
  {
    $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
    $sheet->setCellValue('B'.$numrow, $row['nama']);
    if($row['saldo'] >= 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
      $sum_beban_operasional_debit += $row['saldo'];
    } else if($row['saldo'] < 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
      $sum_beban_operasional_kredit += abs($row['saldo']);
    }

    $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
  }
}

$total_beban = $sum_beban_operasional_debit - $sum_beban_operasional_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Beban Operasional' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
$sheet->setCellValue('D'.$numrow, $total_beban);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup beban operasional

// laba bersih operasional
$laba_rugi_operasional = $laba_kotor - $total_beban; 

$sheet->setCellValue('A'.$numrow, 'Laba (Rugi) Bruto (Gross Profit)' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->setCellValue('D'.$numrow, $laba_rugi_operasional);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup laba bersih operasional


// laba diluar usaha
$sum_pend_beban_lain_debit = 0;
$sum_pend_beban_lain_kredit = 0;
if(isset($laba_rugi_data[0][710]))
{
  foreach ($laba_rugi_data[0][710] as $key => $row)
  {
    $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
    $sheet->setCellValue('B'.$numrow, $row['nama']);
    if($row['saldo'] >= 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
        $sum_pend_beban_lain_debit += $row['saldo'];
    } else if($row['saldo'] < 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
        $sum_pend_beban_lain_kredit += abs($row['saldo']);
    }

    $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
  }
}

$total_pend_beban_lain = $sum_pend_beban_lain_kredit - $sum_pend_beban_lain_debit;

$sheet->setCellValue('A'.$numrow, 'Total Pendapatan (Beban) Diluar Usaha' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
$sheet->setCellValue('D'.$numrow, $total_pend_beban_lain);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup laba diluar usaha


// laba sebelum pajak
$laba_rugi_sebelum_pajak = $laba_rugi_operasional + $total_pend_beban_lain;

$sheet->setCellValue('A'.$numrow, 'Laba (Rugi) Sebelum Pajak' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->setCellValue('D'.$numrow, $laba_rugi_sebelum_pajak);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup laba sebelum pajak


// pajak
$sum_beban_pajak_debit = 0;
$sum_beban_pajak_kredit = 0;
if(isset($laba_rugi_data[0][810]))
{
  foreach ($laba_rugi_data[0][810] as $key => $row)
  {
    $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
    $sheet->setCellValue('B'.$numrow, $row['nama']);
    if($row['saldo'] >= 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
        $sum_beban_pajak_debit += $row['saldo'];
    } else if($row['saldo'] < 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');
        $sum_beban_pajak_kredit += abs($row['saldo']);
    }

    $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
    $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
  }
}

$total_pajak = $sum_beban_pajak_debit-$sum_beban_pajak_kredit; 

$sheet->setCellValue('A'.$numrow, 'Total Pajak' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
$sheet->setCellValue('D'.$numrow, $total_pajak);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup pajak


// laba sebelum pajak
$laba_rugi_setelah_pajak = $laba_rugi_sebelum_pajak - $total_pajak;

$sheet->setCellValue('A'.$numrow, 'Laba (Rugi) Setelah Pajak' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1
$sheet->setCellValue('D'.$numrow, $laba_rugi_setelah_pajak);
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup laba sebelum pajak


// Set width kolom
$sheet->getColumnDimension('A')->setWidth(20); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
// Set judul file excel nya
$sheet->setTitle("Laporan Laba Rugi");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_laporan_labarugi'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 