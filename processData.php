<?
require_once("../Classes/PHPExcel.php");
require_once '../Classes/PHPExcel/IOFactory.php';
$reader = PHPExcel_IOFactory::createReader('Excel5');

$geneAssociationExcel = $reader->load("upload/file1.xls");
$sheet = $geneAssociationExcel->getSheet(0);

$highestRow = $sheet->getHighestRow();

$testArray = array();

for ($row = 2; $row <= $highestRow; $row++) {
	if ($sheet->getCellByColumnAndRow(1, $row)->getValue() !== 'C') {
		continue;
	}
	$GO_ID = $sheet->getCellByColumnAndRow(0, $row)->getValue();
	$Gene_Name = $sheet->getCellByColumnAndRow(2, $row)->getValue();
	if (isset($testArray[$GO_ID][$Gene_Name]) === false) {
		$testArry[$GO_ID][$Gene_Name] = true;
		if (isset($testArray[$GO_ID]['count'])) {
			$testArray[$GO_ID]['count']++;		
		} else {
			$testArray[$GO_ID]['count'] = 1;	
		}
	}
}

var_dump($testArray);

/*
for ($row = 2; $row <= key($testArry); $row++) {


}


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// 設置屬性
$objPHPExcel->getProperties()->setCreator("測試作者")//作者
   ->setLastModifiedBy("測試修改者")//最後修改者
   ->setTitle("測試標題")//標題
   ->setSubject("測試主旨")//主旨
   ->setDescription("測試註解")//註解
   ->setKeywords("測試標記")//標記
   ->setCategory("測試類別");//類別
//Create a first sheet
$objPHPExcel->setActiveSheetIndex(0);


//行號
$excel_line = 1;

//產生第一列
$objPHPExcel->getActiveSheet()->setCellValue("A{$excel_line}", "測試文字");

$excel_line++;


//Excel檔名
$filename = "history_report_".time().".xls";


//Excel下載檔
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//20003格式
$objWriter->save('output/'.$filename);
*/
?>





