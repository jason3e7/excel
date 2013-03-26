<?php
//引入PHPExcel函式庫
require_once("../Classes/PHPExcel.php");
require_once("../Classes/PHPExcel/IOFactory.php");

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

//設定欄位寬度
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

//行號
$excel_line = 1;

//產生第一列
$objPHPExcel->getActiveSheet()->setCellValue("A{$excel_line}", "測試文字");
$objPHPExcel->getActiveSheet()->mergeCells("A{$excel_line}:F{$excel_line}");//合併欄位
$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}")->getFont()->setSize(20);//文字大小
$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}")->getFont()->setBold(true);//粗體
//$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}")->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);//下底線
$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$excel_line++;

$objPHPExcel->getActiveSheet()->setCellValue("A{$excel_line}", '');
$objPHPExcel->getActiveSheet()->mergeCells("A{$excel_line}:F{$excel_line}");//合併欄位
$objPHPExcel->getActiveSheet()->getRowDimension($excel_line)->setRowHeight(230);//列高
$excel_line++;

//產生列
$objPHPExcel->getActiveSheet()->setCellValue("A{$excel_line}", "A3");
$objPHPExcel->getActiveSheet()->setCellValue("B{$excel_line}", "B3");
$objPHPExcel->getActiveSheet()->mergeCells("B{$excel_line}:C{$excel_line}");//合併欄位
$objPHPExcel->getActiveSheet()->setCellValue("D{$excel_line}", "D3");
$objPHPExcel->getActiveSheet()->setCellValue("E{$excel_line}", "E3");
$objPHPExcel->getActiveSheet()->setCellValue("F{$excel_line}", "F3");
$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$objPHPExcel->getActiveSheet()->getStyle("B{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$objPHPExcel->getActiveSheet()->getStyle("D{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$objPHPExcel->getActiveSheet()->getStyle("E{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$objPHPExcel->getActiveSheet()->getStyle("F{$excel_line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//置中
$objPHPExcel->getActiveSheet()->getStyle("A{$excel_line}:F{$excel_line}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直置中
$objPHPExcel->getActiveSheet()->getRowDimension($excel_line)->setRowHeight(25);//列高

//Excel檔名
$filename = "history_report_".time().".xls";

//產生header
/*
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename" );
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");
*/

//產生Excel下載檔
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//20003格式
$objWriter->save('output/'.$filename);
?>
