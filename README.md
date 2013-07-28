#Only Windows So Sorry

##Environment
windows
php-5.3.26-Win32-VC9-x86<br />
http://windows.php.net/download/

##PHP Classes
PEAR-1.9.4<br />

PHPExcel-1.7.8<br />
http://phpexcel.codeplex.com/

Spreadsheet_Excel_Writer<br />
https://github.com/pear/Spreadsheet_Excel_Writer<br />
+ OLE
+ http://pear.php.net/package/OLE/download

###Change Something

##How to use
1.download php<br />
2.download project<br />
3.php processData.php<br />

###Setting
php.ini<br />
+ php memery limit
+ time_zone
+ allow_call_time_pass_reference to true(maybe)

###Example
php excel\processData.php excel\files\file1.xls excel\files\file2.xls C 0<br />
php excel\processData.php excel\files\file1.xls excel\files\file2.xls C 5<br />
