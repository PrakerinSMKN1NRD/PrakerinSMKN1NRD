<?php
session_start();
error_reporting(E_ALL);

require_once '../plugins/excel/PHPExcel.php';
require_once '../config/config.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

if (isset($_SESSION['login']) and $_SESSION['login']==1) {		
		$user=$_SESSION['user'];
		$x=mysql_query("select * from siswa where username='$user'");
		$y=mysql_fetch_array($x);
		$nis=$y['nis'];
}

$query="select * from jurnal_harian where nis='$nis' order by no_id";
$hasil=mysql_query($query);
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Samsu Rizal")
      ->setLastModifiedBy("Samsu Rizal")
      ->setTitle("Backup Data Jurnal")
      ->setSubject("Backup Data Jurnal")
       ->setDescription("Data Jurnal")
       ->setKeywords("Data Jurnal")
       ->setCategory("Jurnal");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'No.')
       ->setCellValue('B1', 'Tanggal')
       ->setCellValue('C1', 'NIS')
	   ->setCellValue('D1', 'Nama Kegiatan')
	   ->setCellValue('E1', 'Uraian');
 
$baris=2;
$no=0;
$nomer=1;			
while($row=mysql_fetch_array($hasil)){
$no=$no+1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $nomer)
     ->setCellValue("B$baris", $row['tgl'])
     ->setCellValue("C$baris", $row['nis'])
	 ->setCellValue("D$baris", $row['nama_keg'])
	 ->setCellValue("E$baris", $row['uraian']);
$baris = $baris + 1;
$nomer++;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Jurnal');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client?s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Data Jurnal Persiswa.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>