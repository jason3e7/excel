<?php
	/*
	echo $_FILES["file"]["error"];
	echo "檔案名稱: " . $_FILES["file"]["name"]."<br/>";
	echo "檔案類型: " . $_FILES["file"]["type"]."<br/>";
	echo "檔案大小: " . ($_FILES["file"]["size"] / 1024)." Kb<br />";
	echo "暫存名稱: " . $_FILES["file"]["tmp_name"];
	*/
	copy($_FILES["gene_association"]["tmp_name"],"upload/".$_FILES["gene_association"]["name"]);
	copy($_FILES["eisen"]["tmp_name"],"upload/".$_FILES["eisen"]["name"]);
	
	echo "檔案名稱: " . $_FILES["gene_association"]["name"]."<br/>";
	echo "檔案名稱: " . $_FILES["eisen"]["name"]."<br/>";
?>
