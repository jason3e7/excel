<?
require_once("../Classes/PHPExcel.php");
require_once '../Classes/PHPExcel/IOFactory.php';
$reader = PHPExcel_IOFactory::createReader('Excel5');

$geneAssociationExcel = $reader->load("upload/file1_full.xls");
$geneAssociationSheet = $geneAssociationExcel->getSheet(0);
$eisenExcel = $reader->load("upload/file2_full.xls");
$eisenSheet = $eisenExcel->getSheet(0);

$highestRow = $eisenSheet->getHighestRow();
var_dump("block:1");
$mappingArray = array();
for ($row = 2; $row <= $highestRow; $row++) {
	$Gene_Name = $eisenSheet->getCellByColumnAndRow(0, $row)->getValue();
	$mappingArray[$Gene_Name][0] = true; 
	
	for ($column = 1; $column <= 80; $column++) {
		$mappingArray[$Gene_Name][$column] = $eisenSheet->getCellByColumnAndRow($column, $row)->getValue();
	}
}

$highestRow = $geneAssociationSheet->getHighestRow();
var_dump("block:2");
$sourceArray = array();
for ($row = 2; $row <= $highestRow; $row++) {
	if ($geneAssociationSheet->getCellByColumnAndRow(1, $row)->getValue() !== 'C') {
		continue;
	}
	$GO_ID = $geneAssociationSheet->getCellByColumnAndRow(0, $row)->getValue();
	$Gene_Name = strtok($geneAssociationSheet->getCellByColumnAndRow(2, $row)->getValue(), "|");

	if(isset($mappingArray[$Gene_Name][0]) === false) {
		continue;
	}

	if (isset($sourceArray[$GO_ID][$Gene_Name]) === false) {
		$sourceArray[$GO_ID][$Gene_Name] = true;
	
		if (isset($sourceArray[$GO_ID]['count'])) {
			$sourceArray[$GO_ID]['count']++;		
		} else {
			$sourceArray[$GO_ID]['count'] = 1;	
		}
		
	}
}

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("jason3e7")//作者
   ->setLastModifiedBy("測試修改者")//最後修改者
   ->setTitle("測試標題")//標題
   ->setSubject("測試主旨")//主旨
   ->setDescription("測試註解")//註解
   ->setKeywords("測試標記")//標記
   ->setCategory("測試類別");//類別

$objPHPExcel->setActiveSheetIndex(0);
$excel_line = 1;
var_dump("block:3");
foreach ($sourceArray as $key => $GO_ID) {
	if ($GO_ID['count'] > 30) {
		foreach ($GO_ID as $Gene_Name => $value) {
			if ($Gene_Name != 'count') {	
				$objPHPExcel->getActiveSheet()->setCellValue("A{$excel_line}", $key);
				$objPHPExcel->getActiveSheet()->setCellValue("B{$excel_line}", $Gene_Name);
				for ($column = 1; $column <= 80; $column++) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $excel_line, $mappingArray[$Gene_Name][$column]);
				}
				$excel_line++;
			}
		}
	}
}

//Excel檔名
$filename = "Report_".time().".xls";

var_dump("block:4");
//Excel下載檔
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//20003格式
$objWriter->save('output/'.$filename);

?>
