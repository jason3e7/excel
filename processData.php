<?
if ($argc !== 5) {
	die("Usage: processData.php <GeneAssociation source> <Eisen source> <Class> <Threshold>\n");
} 
// get and use remaining arguments
$geneAssociationFilename = $argv[1];
$eisenFilename = $argv[2];
$filterClass = $argv[3];
$threshold = $argv[4];

gc_enable();

date_default_timezone_set("Asia/Taipei");
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/Spreadsheet/Excel/Writer.php'; 
$reader = PHPExcel_IOFactory::createReader('Excel5');

echo("Read geneAssociation file\n");
$geneAssociationExcel = $reader->load($geneAssociationFilename);
echo("Read geneAssociation file Done\n");
$geneAssociationSheet = $geneAssociationExcel->getSheet(0);
echo("Read eisen file\n");
$eisenExcel = $reader->load($eisenFilename);
echo("Read eisen file Done\n");
$eisenSheet = $eisenExcel->getSheet(0);

$highestRow = $eisenSheet->getHighestRow();
echo("Create mapping table from eisen file\n");
$mappingArray = array();
for ($row = 1; $row <= $highestRow; $row++) {
	$Gene_Name = $eisenSheet->getCellByColumnAndRow(0, $row)->getValue();
	$mappingArray[$Gene_Name][0] = true; 
	if ($row % 1000 === 0) {
		echo($row . "Lines Complete\n");
	}
	for ($column = 1; $column <= 80; $column++) {
		$mappingArray[$Gene_Name][$column] = $eisenSheet->getCellByColumnAndRow($column, $row)->getValue();
	}
}
echo("Create mapping table from eisen file Done\n");
echo("Memory usage:" . memory_get_usage() . "\n");
unset($eisenExcel);
unset($eisenSheet);
gc_collect_cycles();
echo("Memory usage:" . memory_get_usage() . "\n");
echo("Create source table from geneAssociation file\n");
$highestRow = $geneAssociationSheet->getHighestRow();
$sourceArray = array();
for ($row = 2; $row <= $highestRow; $row++) {
	if ($row % 1000 === 0) {
		echo($row . "Lines Complete\n");
	}
	if ($geneAssociationSheet->getCellByColumnAndRow(1, $row)->getValue() !== $filterClass) {
		continue;
	}
	$GO_ID = $geneAssociationSheet->getCellByColumnAndRow(0, $row)->getValue();
	$Gene_Name = strtok($geneAssociationSheet->getCellByColumnAndRow(2, $row)->getValue(), "|");

	if(isset($mappingArray[$Gene_Name][0]) === false) {
		continue;
	}

	if (isset($sourceArray[$Gene_Name][$GO_ID]) === false) {
		$sourceArray[$Gene_Name][$GO_ID] = true;
	
		if (isset($sourceArray[$Gene_Name]['count'])) {
			$sourceArray[$Gene_Name]['count']++;		
		} else {
			$sourceArray[$Gene_Name]['count'] = 1;	
		}	
	}
}
echo("Create source table from geneAssociation file Done\n");
echo("Memory usage:" . memory_get_usage() . "\n");
unset($geneAssociationExcel);
unset($geneAssociationSheet);
gc_collect_cycles();
echo("Memory usage:" . memory_get_usage() . "\n");

$filename = "Report_exist_".time().".xls";
$filenameEmpty = "Report_empty_".time().".xls";
$excelOutput = new Spreadsheet_Excel_Writer($filename);
$excelOutput->setVersion(8); 
$excelOutputEmpty = new Spreadsheet_Excel_Writer($filenameEmpty);
$excelOutputEmpty->setVersion(8); 

$worksheet =& $excelOutput->addWorksheet('0');
$worksheet->setInputEncoding('utf-8');
$worksheetEmpty =& $excelOutputEmpty->addWorksheet('0');
$worksheetEmpty->setInputEncoding('utf-8');

$excel_line = 0;

$worksheet->write($excel_line, 0, 'GeneName');
$worksheet->write($excel_line, 1, 'GO:ID');
$worksheetEmpty->write($excel_line, 0, 'GeneName');

for ($column = 1; $column <= 80; $column++) {
	$worksheet->write($excel_line, $column + 1, $mappingArray['GeneName'][$column]);
	$worksheetEmpty->write($excel_line, $column, $mappingArray['GeneName'][$column]);
}
$excel_line++;

echo("Create exist output table\n");
// exist table
foreach ($sourceArray as $key => $Gene_Name) {
	if ($Gene_Name['count'] >= intval($threshold)) {
		foreach ($Gene_Name as $GO_ID => $value) {
			if ($GO_ID != 'count') {
				$worksheet->write($excel_line, 0, $key);
				$worksheet->write($excel_line, 1, $GO_ID);	
				for ($column = 1; $column <= 80; $column++) {
					$worksheet->write($excel_line, $column + 1, $mappingArray[$key][$column]);	
				}
				$excel_line++;
				
				if ($excel_line % 1000 === 0) {
					echo($excel_line . "Lines Complete\n");
				}
			}
		}
	}
}
// empty table
$excel_line = 1;
foreach ($mappingArray as $key => $Gene_Name) {
	if ($key === 'GeneName') {
		continue;
	}
	if (isset($sourceArray[$key]) === false) {
		$worksheetEmpty->write($excel_line, 0, $key);
		for ($column = 1; $column <= 80; $column++) {
			$worksheetEmpty->write($excel_line, $column + 1, $mappingArray[$key][$column]);	
		}
		$excel_line++;
	}
}

echo("Create output table Done\n");
echo("Memory usage:" . memory_get_usage() . "\n");
unset($sourceArray);
unset($mappingArray);
gc_collect_cycles();
echo("Memory usage:" . memory_get_usage() . "\n");

echo("Write output\n");
$excelOutput->close(); 
$excelOutputEmpty->close(); 
echo("Write output Done\n");
?>