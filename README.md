#Only Windows So Sorry

##Environment
windows
php-5.3.26-Win32-VC9-x86
http://windows.php.net/download/

##PHP Classes
PEAR-1.9.4

PHPExcel-1.7.8
http://phpexcel.codeplex.com/

Spreadsheet_Excel_Writer
https://github.com/pear/Spreadsheet_Excel_Writer
	OLE
	http://pear.php.net/package/OLE/download

##How to use
1.php processData.php
2.
3.Output at php dir

###Setting
config
php memery limit
allow_call_time_pass_reference to true in your php.ini
time zone

###Example
php excel\processData.php excel\files\file1.xls excel\files\file2.xls C 0
php excel\processData.php excel\files\file1.xls excel\files\file2.xls C 5