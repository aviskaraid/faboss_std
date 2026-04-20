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



$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

// pemrosesan data
// penjualan lokal
$sheet->setCellValue('A5', 'Aktivitas Operasi' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A5:D5'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A5:D5')->applyFromArray($style_row);

// aktivasi operasi
$i=1;
$total_aktivitas_operasi = 0;
if(!empty($activitas_operasi)) {
    foreach ($activitas_operasi as $act_op) {
    	$sheet->setCellValue('A'.$numrow, $i++);
        $sheet->setCellValue('B'.$numrow, $act_op['nama_kategori']);

        $result = 0;
        foreach ($act_op['akun_sumber'] as $nilai) {
            $result += $nilai['nilai'];
        }
        if($result < 0) { 
	        $sheet->setCellValue('C'.$numrow, '('.abs($result).')');
	        $sheet->setCellValue('D'.$numrow, '');
        } else {  
	        $sheet->setCellValue('C'.$numrow, $result);
	        $sheet->setCellValue('D'.$numrow, '');
        }
        $total_aktivitas_operasi += $result;

		$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }


	$sheet->setCellValue('A'.$numrow, 'Total Kas Dari Aktivitas Operasi');
	$sheet->mergeCells('A'.$numrow.':C'.$numrow);
    if($total_aktivitas_operasi < 0) { 
        $sheet->setCellValue('D'.$numrow, '('.abs($total_aktivitas_operasi).')');
    } else {
        $sheet->setCellValue('D'.$numrow, $total_aktivitas_operasi);
    }

    $sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
}
// tutup aktivitas operaisonal

// aktivitas investasi
$sheet->setCellValue('A'.$numrow, 'Aktivitas Investasi' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

$i=1;
$total_activitas_investasi = 0;
if(!empty($activitas_investasi)) {
    foreach ($activitas_investasi as $act_inv) {
    	$sheet->setCellValue('A'.$numrow, $i++);
        $sheet->setCellValue('B'.$numrow, $act_inv['nama_kategori']);

        $result = 0;
        foreach ($act_inv['akun_sumber'] as $nilai) {
            $result += $nilai['nilai'];
        }
        if($result < 0) { 
	        $sheet->setCellValue('C'.$numrow, '('.abs($result).')');
	        $sheet->setCellValue('D'.$numrow, '');
        } else {  
	        $sheet->setCellValue('C'.$numrow, $result);
	        $sheet->setCellValue('D'.$numrow, '');
        }
        $total_activitas_investasi += $result;


		$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }


	$sheet->setCellValue('A'.$numrow, 'Total Kas Dari Aktivitas Investasi');
	$sheet->mergeCells('A'.$numrow.':C'.$numrow);
    if($total_activitas_investasi < 0) { 
        $sheet->setCellValue('D'.$numrow, '('.abs($total_activitas_investasi).')');
    } else {
        $sheet->setCellValue('D'.$numrow, $total_activitas_investasi);
    }

    $sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
}
// tutup aktivitas investasi

// aktivitas pendanaan
$sheet->setCellValue('A'.$numrow, 'Aktivitas Pendanaan' ); // Set kolom A1 dengan tulisan 
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

$i=1;
$total_activitas_pendanaan = 0;
if(!empty($activitas_pendanaan)) {
    foreach ($activitas_pendanaan as $act_pend) {
    	$sheet->setCellValue('A'.$numrow, $i++);
        $sheet->setCellValue('B'.$numrow, $act_pend['nama_kategori']);

        $result = 0;
        foreach ($act_pend['akun_sumber'] as $nilai) {
            $result += $nilai['nilai'];
        }
        if($result < 0) { 
	        $sheet->setCellValue('C'.$numrow, '('.abs($result).')');
	        $sheet->setCellValue('D'.$numrow, '');
        } else {  
	        $sheet->setCellValue('C'.$numrow, $result);
	        $sheet->setCellValue('D'.$numrow, '');
        }
        $total_activitas_pendanaan += $result;

		$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }


	$sheet->setCellValue('A'.$numrow, 'Total Kas Dari Aktivitas Pendanaan');
	$sheet->mergeCells('A'.$numrow.':C'.$numrow);
    if($total_activitas_pendanaan < 0) { 
        $sheet->setCellValue('D'.$numrow, '('.abs($total_activitas_pendanaan).')');
    } else {
        $sheet->setCellValue('D'.$numrow, $total_activitas_pendanaan);
    }

    $sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

    $numrow++;
}
// tutup aktivitas pendanaan


// Perhitungan arus kas berjalan 
$arus_kas = $total_aktivitas_operasi + $total_activitas_investasi + $total_activitas_pendanaan;

if($arus_kas < 0) { 
    $sheet->setCellValue('A'.$numrow, 'Penurunan Kas');
	$sheet->mergeCells('A'.$numrow.':C'.$numrow);
	$sheet->setCellValue('D'.$numrow, '('.abs($arus_kas).')');

	$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
} else {
    $sheet->setCellValue('A'.$numrow, 'Penambahan Kas');
	$sheet->mergeCells('A'.$numrow.':C'.$numrow);
	$sheet->setCellValue('D'.$numrow, $arus_kas);

	$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
}

$numrow++;

$sheet->setCellValue('A'.$numrow, 'Saldo Kas/Bank Awal');
$sheet->mergeCells('A'.$numrow.':C'.$numrow);
$sheet->setCellValue('D'.$numrow, $saldo_awal_tahun);

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;

$saldo_akhir =  $saldo_awal_tahun + $arus_kas;


$sheet->setCellValue('A'.$numrow, 'Saldo Kas/Bank Akhir');
$sheet->mergeCells('A'.$numrow.':C'.$numrow);
if($saldo_akhir < 0) { 
	$sheet->setCellValue('D'.$numrow, '('.abs($saldo_akhir).')');
} else {
	$sheet->setCellValue('D'.$numrow, $saldo_akhir);
}

$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(20); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set judul file excel nya
$sheet->setTitle("Laporan Arus Kas");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_laporan_arus_kas'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 