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
$sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1:G1')->applyFromArray($style_col);

$sheet->setCellValue('A2', 'Buku Besar'); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A2:G2'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A2')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A2:G2')->applyFromArray($style_col);

$sheet->setCellValue('A3', date("d/n/Y", strtotime($tglAwal)).' s/d '.date("d/n/Y", strtotime($tglAkhir))); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A3:G3'); // Set Merge Cell pada kolom A1 sampai E1
$sheet->getStyle('A3')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A3:G3')->applyFromArray($style_col);

// tabel data
$sheet->setCellValue('A5', "Tanggal"); 
$sheet->setCellValue('B5', "No. Transaksi"); 
$sheet->setCellValue('C5', "Keterangan"); 
$sheet->setCellValue('D5', "Debit");
$sheet->setCellValue('E5', "Kredit"); 
$sheet->setCellValue('F5', "Saldo Akhir Debit"); 
$sheet->setCellValue('G5', "Saldo Akhir Kredit"); 

$sheet->getStyle('A5')->applyFromArray($style_col);
$sheet->getStyle('B5')->applyFromArray($style_col);
$sheet->getStyle('C5')->applyFromArray($style_col);
$sheet->getStyle('D5')->applyFromArray($style_col);
$sheet->getStyle('E5')->applyFromArray($style_col);
$sheet->getStyle('F5')->applyFromArray($style_col);
$sheet->getStyle('G5')->applyFromArray($style_col);


date_default_timezone_set('Asia/Makassar');
$now = date("Y-m-d");

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

		$totalDebit = 0;
        $totalKredit = 0;
        $sum = 0;

        if ($account_data) {
        	$sum = intval($account_data['saldo_awal']);

	        // total
	        $sheet->setCellValue('A'.$numrow, '['.$account_data['noakun'].'] '.$account_data['nama'].' | Saldo Awal' ); // Set kolom A1 dengan tulisan "DATA SISWA"
		    $sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
			$sheet->getStyle('A'.$numrow.':G'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
			$sheet->getStyle('A'.$numrow.':G'.$numrow)->getFont()->setSize(9); // Set font size 15 untuk kolom A1

			$sheet->setCellValue('D'.$numrow, $totalDebit);
			$sheet->setCellValue('E'.$numrow, abs($totalKredit));

	        if($account_data['id_perkiraan'] == 1){
	            if($sum >= 0) {
	        		$sheet->setCellValue('F'.$numrow, $sum);
	        		$sheet->setCellValue('G'.$numrow, '');
	            } else {
	        		$sheet->setCellValue('F'.$numrow, '');
	        		$sheet->setCellValue('G'.$numrow, abs($sum));
	            }
	        } else {
	            if($sum <= 0) {
	        		$sheet->setCellValue('F'.$numrow, abs($sum));
	        		$sheet->setCellValue('G'.$numrow, '');
	            } else {
	        		$sheet->setCellValue('F'.$numrow, '');
	        		$sheet->setCellValue('G'.$numrow, $sum);
	            }
	        }

	        $sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);

	      	$numrow++;
        }

        foreach ($journal_data as $data) {

	    	$sheet->setCellValue('A'.$numrow, date("d/n/Y", strtotime($data['tgl'])));
	    	$sheet->setCellValue('B'.$numrow, $data['no_trans_format']);
	    	$sheet->setCellValue('C'.$numrow, $data['keterangan']);

	        if($data['id_perkiraan'] == 1) {
	            
	            $sum += intval($data['nilai']);
	            $totalDebit += intval($data['nilai']);

	            if($sum >= 0){
	        		$sheet->setCellValue('D'.$numrow, $sum);
	        		$sheet->setCellValue('E'.$numrow, '');
	        		$sheet->setCellValue('F'.$numrow, $sum);
	        		$sheet->setCellValue('G'.$numrow, '');
	            } else {
	        		$sheet->setCellValue('D'.$numrow, $sum);
	        		$sheet->setCellValue('E'.$numrow, '');
	        		$sheet->setCellValue('F'.$numrow, '');
	        		$sheet->setCellValue('G'.$numrow, abs($sum));
	            }
	        } else {
	        	$sum -= intval($data['nilai']);
	            $totalKredit += intval($data['nilai']);

	            if($sum >= 0){
	        		$sheet->setCellValue('D'.$numrow, '');
	        		$sheet->setCellValue('E'.$numrow, $sum);
	        		$sheet->setCellValue('F'.$numrow, $sum);
	        		$sheet->setCellValue('G'.$numrow, '');
	            } else {
	        		$sheet->setCellValue('D'.$numrow, '');
	        		$sheet->setCellValue('E'.$numrow, $sum);
	        		$sheet->setCellValue('F'.$numrow, '');
	        		$sheet->setCellValue('G'.$numrow, abs($sum));
	            }
	        }

	    	$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);


	    	$numrow++;
	    }

	    // total
	    $sheet->setCellValue('A'.$numrow, '['.$account_data['noakun'].'] '.$account_data['nama'].' | Saldo Akhir' ); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $sheet->mergeCells('A'.$numrow.':C'.$numrow); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A'.$numrow.':G'.$numrow)->getFont()->setBold(TRUE); // Set bold kolom A1
		$sheet->getStyle('A'.$numrow.':G'.$numrow)->getFont()->setSize(9); // Set font size 15 untuk kolom A1

		$sheet->setCellValue('D'.$numrow, $totalDebit);
		$sheet->setCellValue('E'.$numrow, abs($totalKredit));

	    if($account_data['id_perkiraan'] == 1){
	        if($sum >= 0) {
	    		$sheet->setCellValue('F'.$numrow, $sum);
	    		$sheet->setCellValue('G'.$numrow, '');
	        } else {
	    		$sheet->setCellValue('F'.$numrow, '');
	    		$sheet->setCellValue('G'.$numrow, abs($sum));
	        }
	    } else {
	        if($sum <= 0) {
	    		$sheet->setCellValue('F'.$numrow, abs($sum));
	    		$sheet->setCellValue('G'.$numrow, '');
	        } else {
	    		$sheet->setCellValue('F'.$numrow, '');
	    		$sheet->setCellValue('G'.$numrow, $sum);
	        }
	    }


	  	$sheet->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);
	  	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
	  	$sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
	  	$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
	  	$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);

	  	$numrow++;

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(15); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(45); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
$sheet->getColumnDimension('E')->setWidth(20); // Set width kolom E
$sheet->getColumnDimension('F')->setWidth(20); // Set width kolom E
$sheet->getColumnDimension('G')->setWidth(20); // Set width kolom E

// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
$sheet->getDefaultRowDimension()->setRowHeight(-1);

$writer = new Xls($spreadsheet); // instantiate Xlsx
 
$filename = time().'_bukubesar'; // set filename for excel file to be exported

// header('Content-Type: application/vnd.ms-excel'); // generate excel file

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
header('Cache-Control: max-age=0');
$writer->save('php://output');	// download file 

?>
