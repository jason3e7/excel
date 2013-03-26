<?
require_once '../Classes/PHPExcel/IOFactory.php';
$reader = PHPExcel_IOFactory::createReader('Excel5'); // 讀取舊版 excel 檔案

$PHPExcel = $reader->load("upload/file1.xls"); // 檔案名稱

$sheet = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)

$highestRow = $sheet->getHighestRow(); // 取得總列數

// 一次讀取一列
for ($row = 2; $row <= $highestRow; $row++) {
	if($sheet->getCellByColumnAndRow(1, $row)->getValue() !== 'C' )
		continue;
	for ($column = 0; $column <= 9; $column++) {
		$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
		echo $val . ' ';
	}
	echo "<br>";
}
?>
