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


$sheet->setCellValue('A2', "Laporan Perubahan Ekuitas" ); // Set kolom A1 dengan tulisan 
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
$numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4

// saldo awal per tahun
$modal_awal = $saldo_awal;
$total_laba_rugi = $labarugi;

$sheet->setCellValue('A'.$numrow, 'Saldo per '.date("d/m/Y", strtotime($tahun.'/'.$bln.'/1')) ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('D'.$numrow, abs($modal_awal));
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup saldo awal pertahun


// penambahan modal
$sheet->setCellValue('A'.$numrow, 'Penambahan'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':D'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

$sum_ekuitas_debit = 0;
// cek apakah ada data di perubahan ekuitas
if(isset($perubahan_ekuitas_data[0][310]))
{

    // jika total laba rugi >= 0, maka jalankan perintah dibawah
    if($total_laba_rugi >= 0) {  

        $sheet->setCellValue('A'.$numrow, '');
        $sheet->setCellValue('B'.$numrow, 'Laba Bersih' ); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('B'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

        $sheet->setCellValue('D'.$numrow, abs($total_laba_rugi));


        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow.':C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    } 

    // Mendapatkan noakun 11 
    foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
    {
        if($row['saldo'] < 0){ 

            $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
            $sheet->setCellValue('B'.$numrow, $row['nama']);
            $sheet->setCellValue('C'.$numrow, $row['saldo']);
            $sheet->setCellValue('D'.$numrow, '');

            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

            $sum_ekuitas_debit += abs($row['saldo']); 

            $numrow++;
        } 
    }

    // perhitungan
    if($total_laba_rugi >= 0) { 
      // jumlah penambahan ekuitas
      $sum_ekuitas_debit = $sum_ekuitas_debit + abs($total_laba_rugi);  
    } else {
      // jumlah penambahan ekuitas
      $sum_ekuitas_debit = $sum_ekuitas_debit; 
    }
}

$sheet->setCellValue('A'.$numrow, 'Total Penambahan' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('D'.$numrow, abs($sum_ekuitas_debit));
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;
// tutup penambahan modal


// pengurangan modal
$sheet->setCellValue('A'.$numrow, 'Pengurangan'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':D'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':D'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_row);

$numrow++;

$sum_ekuitas_kredit = 0;
// cek apakah ada data di perubahan ekuitas
if(isset($perubahan_ekuitas_data[0][310]))
{

    // jika total laba rugi >= 0, maka jalankan perintah dibawah
    if($total_laba_rugi < 0) {  

        $sheet->setCellValue('A'.$numrow, '');
        $sheet->setCellValue('B'.$numrow, 'Rugi Bersih' ); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('B'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

        $sheet->setCellValue('D'.$numrow, abs($total_laba_rugi));


        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow.':C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    } 

    // Mendapatkan noakun 11 
    foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
    {
        if($row['saldo'] > 0){ 

            $sheet->setCellValue('A'.$numrow, strval($row['noakun']));
            $sheet->setCellValue('B'.$numrow, $row['nama']);
            $sheet->setCellValue('C'.$numrow, $row['saldo']);
            $sheet->setCellValue('D'.$numrow, '');

            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

            $sum_ekuitas_kredit += abs($row['saldo']); 

            $numrow++;
        } 
    }

    // perhitungan
    if($total_laba_rugi < 0) { 
      // jumlah penambahan ekuitas
      $sum_ekuitas_kredit = $sum_ekuitas_kredit + abs($total_laba_rugi);  
    } else {
      // jumlah penambahan ekuitas
      $sum_ekuitas_kredit = $sum_ekuitas_kredit; 
    }
}

$sheet->setCellValue('A'.$numrow, 'Total Pengurangan' ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('D'.$numrow, abs($sum_ekuitas_kredit));
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;

// tutup pengurangan modal

// saldo akhit

$modal_akhir = $modal_awal + $sum_ekuitas_debit - $sum_ekuitas_kredit;


$sheet->setCellValue('A'.$numrow, 'Saldo per '.date("d/n/Y", strtotime($tglAkhir)) ); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
$sheet->getStyle('A'.$numrow.':C'.$numrow)->getFont()->setSize(12); // Set font size 15 untuk kolom A1

$sheet->setCellValue('D'.$numrow, abs($modal_akhir));
$sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1


$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

$numrow++;

// tutup saldo akhir 


// Set width kolom
$sheet->getColumnDimension('A')->setWidth(15); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Set judul file excel nya
$sheet->setTitle("Laporan Perubahan Ekuitas");

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_laporan_perubahan_ekuitas'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');  // download file 