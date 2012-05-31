<?php
session_start();
include_once( 'config.php' );
define(UNIX_TIMESTAMP, date('U'));

$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
if(!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("wkSite", $con);
if(!mysql_query("CREATE TABLE IF NOT EXISTS `wkshdisplay` (id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), content VARCHAR(400), time BIGINT, platform VARCHAR(20), pic VARCHAR(256), instagram VARCHAR(50), ip VARCHAR(15))", $con)) {
	die("Error creating database: " . mysql_error());
}

function save_db($_pic) {
	$_name = addslashes($_REQUEST[name]);
	$_content = addslashes($_REQUEST[content]);
	$_pic = addslashes($_pic);

	// platform
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$platform = 'unknown';
	$platforms = array(
		'iphone', 'ipad', 'ipod', 'mac', 'windows', 'blackberry', 'android', 'linux'
	);
	foreach($platforms as $platforms) {
		if(strstr($agent, $platforms)) {
			$platform = $platforms;
			break;
		}
	}

	return $sql="INSERT INTO wkshdisplay (name, content, time, platform, pic, ip) VALUES ('$_name', '$_content', " . UNIX_TIMESTAMP . ", '$platform', '$_pic', '" . $_SERVER['REMOTE_ADDR'] . "')";
}

function save_image($inPath, $outPath) {
//Download images from remote server
    $in = fopen($inPath, "rb");
    $out = fopen($outPath, "wb");
    while( $chunk = fread($in, 8192) ) {
        fwrite($out, $chunk, 8192);
    }
    fclose($in);
    fclose($out);
}
?>
<html>
<head>
</head>
<body>
<?php
/* DELETE TABLE */
/*
$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
if(!$con) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("wkSite", $con);

$sql = "DROP TABLE wkshdisplay";
if(!mysql_query($sql, $con)) {
	die('[Error] ' . mysql_error());
}
mysql_close($con);
*/
if( isset($_REQUEST[name]) && isset($_REQUEST[content]) ) {
	if( isset($_FILES['file']) ) {
		if ($_FILES["file"]["error"] > 0) {
			echo '<script type="text/javascript">';
			echo '	alert("Image Upload Error: ' . $_FILES["file"]["error"] . '");';
			echo '</script>';
		} else if( strstr($_FILES["file"]["type"], 'image') == false ) {
			echo '<script type="text/javascript">';
			echo '	alert("Image Upload Error: Your File Is Not An Image. Please Try Again! (' . $_FILES["file"]["type"] . ')");';
			echo '</script>';
		} else {
			$target = 'upload_img/' . md5(UNIX_TIMESTAMP) . '_' . rand(0, 9999) . '.' . substr($_FILES["file"]["type"], 6);
			/*
			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			*/
			echo $target;
			if( !move_uploaded_file($_FILES["file"]["tmp_name"], $target) ) {
				echo '<script type="text/javascript">';
				echo '	alert("Image Upload Error: Failed. Please Try Again!");';
				echo '</script>';
			} else {
				$sql = save_db($target);
				if(!mysql_query($sql, $con)) die($sql);
			}
		}
	} else if( isset($_REQUEST[url]) ) {
		$target = 'upload_img/' . md5(UNIX_TIMESTAMP) . '_' . rand(0, 9999) . '.' . array_pop( split('\.', $_REQUEST[url]) );
		save_image($_REQUEST[url], $target);
		$sql = save_db($target);
		if(!mysql_query($sql, $con)) die($sql);
	} else {
		$sql = save_db('');
		if(!mysql_query($sql, $con)) die($sql);
	}

	mysql_close($con);
	header('Location: index.php');
}
?>
</body>
</html>
