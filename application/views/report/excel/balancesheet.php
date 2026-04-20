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


$sheet->setCellValue('A2', "Laporan Laba Rugi" ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A2:D2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1



$sheet->setCellValue('A3', 'Periode '.getBulan($bln).' '.$tahun ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A3:D3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1




date_default_timezone_set('Asia/Makassar');
$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4


// aset
$sheet->setCellValue('A'.$numrow, 'Aset' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

// aset lancar
$sheet->setCellValue('A'.$numrow, 'Aset  Lancar' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;



$sum_aset_lancar_debit = 0;
$sum_aset_lancar_kredit = 0;
if(isset($neraca_saldo_data[0][110]))
{
    foreach ($neraca_saldo_data[0][110] as $key => $row)
    {
          $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
          $sheet->setCellValue('B'.$numrow, $row['nama']);

          if($row['saldo'] >= 0) {
            $sheet->setCellValue('C'.$numrow, $row['saldo']);
            $sheet->setCellValue('D'.$numrow, '');

            $sum_aset_lancar_debit += $row['saldo']; 
          } else {
            $sheet->setCellValue('C'.$numrow, '('.abs($row['saldo']).')');
            $sheet->setCellValue('D'.$numrow, '');

            $sum_aset_lancar_kredit += $row['saldo'];  
          }

          $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
          $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

          $numrow++;
    }
}

$sum_aset_lancar = $sum_aset_lancar_debit + $sum_aset_lancar_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Aset Lancar' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
if($sum_aset_lancar >= 0) {
  $sheet->setCellValue('D'.$numrow, abs($sum_aset_lancar));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($sum_aset_lancar).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup aset lancar




// aset tetap
$sheet->setCellValue('A'.$numrow, 'Aset Tetap' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;


$sum_aset_tetap_debit = 0;
$sum_aset_tetap_kredit = 0;
if(isset($neraca_saldo_data[0][120]))
{
    foreach ($neraca_saldo_data[0][120] as $key => $row)
    {

        $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
        $sheet->setCellValue('B'.$numrow, $row['nama']);

        if($row['saldo'] >= 0) {
          $sheet->setCellValue('C'.$numrow, $row['saldo']);
          $sheet->setCellValue('D'.$numrow, '');

          $sum_aset_tetap_debit += $row['saldo']; 
        } else {
          $sheet->setCellValue('C'.$numrow, '('.abs($row['saldo']).')');
          $sheet->setCellValue('D'.$numrow, '');

          $sum_aset_tetap_kredit += $row['saldo'];  
        }

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;

    }
}

$sum_aset_tetap = $sum_aset_tetap_debit - $sum_aset_tetap_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Aset Tetap' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');

if($sum_aset_tetap >= 0) {
  $sheet->setCellValue('D'.$numrow, abs($sum_aset_tetap));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($sum_aset_tetap).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup aset tetap

// total aset
$total_aset = $sum_aset_lancar + $sum_aset_tetap;

$sheet->setCellValue('A'.$numrow, 'Total Aset' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
if($total_aset >= 0) {
  $sheet->setCellValue('D'.$numrow, abs($total_aset));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($total_aset).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup total aset



// liabilitas dan ekuitas
$sheet->setCellValue('A'.$numrow, 'Liabilitas dan Ekuitas' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

// aset lancar
$sheet->setCellValue('A'.$numrow, 'Liabilitas Jangka Pendek' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;



$sum_liabilitas_jangka_pendek_debit = 0;
$sum_liabilitas_jangka_pendek_kredit = 0;
if(isset($neraca_saldo_data[0][210]))
{
    foreach ($neraca_saldo_data[0][210] as $key => $row)
    {
        $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
        $sheet->setCellValue('B'.$numrow, $row['nama']);

        if($row['saldo'] <= 0) {
          $sheet->setCellValue('C'.$numrow, abs($row['saldo']));
          $sheet->setCellValue('D'.$numrow, '');

          $sum_liabilitas_jangka_pendek_kredit += $row['saldo']; 
        } else {
          $sheet->setCellValue('C'.$numrow, '('.abs($row['saldo']).')');
          $sheet->setCellValue('D'.$numrow, '');

          $sum_liabilitas_jangka_pendek_debit += $row['saldo'];  
        }

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }
}

$sum_liabilitas_jangka_pendek = $sum_liabilitas_jangka_pendek_debit - $sum_liabilitas_jangka_pendek_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Aset Lancar' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
if($sum_liabilitas_jangka_pendek <= 0) {
  $sheet->setCellValue('D'.$numrow, abs($sum_liabilitas_jangka_pendek));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($sum_liabilitas_jangka_pendek).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;

// tutup liabilitas jangka pendek


// liabilitas jangka panjang
$sheet->setCellValue('A'.$numrow, 'Liabilitas Jangka Panjang' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;


$sum_liabilitas_jangka_panjang_debit = 0;
$sum_liabilitas_jangka_panjang_kredit = 0;
if(isset($neraca_saldo_data[0][220]))
{
    foreach ($neraca_saldo_data[0][220] as $key => $row)
    {

        $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
        $sheet->setCellValue('B'.$numrow, $row['nama']);

        if($row['saldo'] <= 0) {
          $sheet->setCellValue('C'.$numrow, abs($row['saldo']));
          $sheet->setCellValue('D'.$numrow, '');

          $sum_liabilitas_jangka_panjang_debit += $row['saldo']; 
        } else {
          $sheet->setCellValue('C'.$numrow, '('.abs($row['saldo']).')');
          $sheet->setCellValue('D'.$numrow, '');

          $sum_liabilitas_jangka_panjang_kredit += $row['saldo'];  
        }

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;

    }
}

$sum_liabilitas_jangka_panjang = $sum_liabilitas_jangka_panjang_debit - $sum_liabilitas_jangka_panjang_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Aset Tetap' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');

if($sum_liabilitas_jangka_panjang <= 0) {
  $sheet->setCellValue('D'.$numrow, abs($sum_liabilitas_jangka_panjang));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($sum_liabilitas_jangka_panjang).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup liabilitas jangka panjang

// total liabilitas
$total_liabilitas = $sum_liabilitas_jangka_pendek + $sum_liabilitas_jangka_panjang;

$sheet->setCellValue('A'.$numrow, 'Total Liabilitas' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');
if($total_liabilitas <= 0) {
  $sheet->setCellValue('D'.$numrow, abs($total_liabilitas));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($total_liabilitas).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup total liabilitas


// ekuitas
$sheet->setCellValue('A'.$numrow, 'Ekuitas' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

$sum_ekuitas_debit = 0;
$sum_ekuitas_kredit = 0;
if(isset($neraca_saldo_data[0][310]))
{
    foreach ($neraca_saldo_data[0][310] as $key => $row)
    {
        $nilai = $row['saldo'];
        if ($row['noakun'] == $set_account['noakun_lb_ditahan']) {
            // laba ditahan
            $nilai += (-1)*$laba_ditahan;
        } else if ($row['noakun'] == $set_account['noakun_lb_sebelumnya']) {
            // laba ditahan
            $nilai += (-1)*$laba_tahun_sebelumnya;
        } 

        $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
        $sheet->setCellValue('B'.$numrow, $row['nama']);

        if($nilai <= 0) {
          $sheet->setCellValue('C'.$numrow, abs($nilai));
          $sheet->setCellValue('D'.$numrow, '');

          $sum_ekuitas_kredit += $nilai; 
        } else {
          $sheet->setCellValue('C'.$numrow, "(".abs($nilai).")");
          $sheet->setCellValue('D'.$numrow, '');

          $sum_ekuitas_debit += $nilai;  
        }

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;

    }
}

$sum_ekuitas = $sum_ekuitas_debit + $sum_ekuitas_kredit;

$sheet->setCellValue('A'.$numrow, 'Total Ekuitas' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');

if($sum_ekuitas <= 0) {
  $sheet->setCellValue('D'.$numrow, abs($sum_ekuitas));
} else {
  $sheet->setCellValue('D'.$numrow, '('.abs($sum_ekuitas).')');
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup ekuitas


// total ekuitas dan liabilitas
$total_liabilitas_and_ekuitas = $total_liabilitas + $sum_ekuitas; 

$sheet->setCellValue('A'.$numrow, 'Total Liabilitas dan Ekuitas' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':B'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('C'.$numrow, '');

if($total_liabilitas_and_ekuitas >= 0) {
  $sheet->setCellValue('D'.$numrow, '('.abs($total_liabilitas_and_ekuitas).')');
} else {
  $sheet->setCellValue('D'.$numrow, abs($total_liabilitas_and_ekuitas));
}
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':B'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
// tutup total ekuitas dan liabilitas





// Set width kolom
$sheet->getColumnDimension('A')->setWidth(20); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set orientasi kertas jadi LANDSCAPE
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
// Set judul file excel nya
$sheet->setTitle("Laporan Neraca");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_laporan_neraca'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 