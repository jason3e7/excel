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

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Spreadsheet/Excel/Writer.php'; 
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

	if (isset($sourceArray[$GO_ID][$Gene_Name]) === false) {
		$sourceArray[$GO_ID][$Gene_Name] = true;
	
		if (isset($sourceArray[$GO_ID]['count'])) {
			$sourceArray[$GO_ID]['count']++;		
		} else {
			$sourceArray[$GO_ID]['count'] = 1;	
		}	
	}
}
echo("Create source table from geneAssociation file Done\n");
echo("Memory usage:" . memory_get_usage() . "\n");
unset($geneAssociationExcel);
unset($geneAssociationSheet);
gc_collect_cycles();
echo("Memory usage:" . memory_get_usage() . "\n");

$filename = "Report_".time().".xls";
$excelOutput = new Spreadsheet_Excel_Writer('output/'.$filename);
$excelOutput->setVersion(8); 

$worksheet =& $excelOutput->addWorksheet('0');
$worksheet->setInputEncoding('utf-8');

$excel_line = 0;

$worksheet->write($excel_line, 0, 'GO:ID');
$worksheet->write($excel_line, 1, 'GeneName');

for ($column = 1; $column <= 80; $column++) {
	$worksheet->write($excel_line, $column + 1, $mappingArray['GeneName'][$column]);
}
$excel_line++;

echo("Create output table\n");
foreach ($sourceArray as $key => $GO_ID) {
	if ($GO_ID['count'] >= intval($threshold)) {
		foreach ($GO_ID as $Gene_Name => $value) {
			if ($Gene_Name != 'count') {
				$worksheet->write($excel_line, 0, $key);
				$worksheet->write($excel_line, 1, $Gene_Name);	
				for ($column = 1; $column <= 80; $column++) {
					$worksheet->write($excel_line, $column + 1, $mappingArray[$Gene_Name][$column]);	
				}
				$excel_line++;
				
				if ($excel_line % 1000 === 0) {
					echo($excel_line . "Lines Complete\n");
				}
			}
		}
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
echo("Write output Done\n");
?>
