<?php
if ($_FILES["file"]["error"] > 0) {
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
} else {
	$target = 'upload_img/' . $_FILES["file"]["name"];
	$ok = 1;
	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	echo "Type: " . $_FILES["file"]["type"] . "<br />";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
	if( move_uploaded_file($_FILES["file"]["tmp_name"], $target) ) {
		echo "Stored in: " . "upload_img/" . $_FILES["file"]["name"];
	} else {
		echo "Failed :(";
	}

/*
	if (file_exists("upload_img/" . $_FILES["file"]["name"])) {
		echo $_FILES["file"]["name"] . " already exists. ";
	} else {
		if( move_uploaded_file($_FILES["file"]["tmp_name"], "upload_img/" . $_FILES["file"]["name"]) )
		else
	}
*/
}
?>
