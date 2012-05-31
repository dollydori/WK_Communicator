<?php
session_start();
include_once( 'config.php' );

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

if(isset($_GET[hub_challenge])) echo $_GET[hub_challenge];

$php_input = file_get_contents('php://input');
date_default_timezone_set('Asia/Shanghai');
$ALL = date("F.j.Y, g:i:s a")." ".$php_input." [from ".$_SERVER['REMOTE_ADDR']."]\r\n";
file_put_contents('log/instagram.activity.log', $ALL, FILE_APPEND);

$url = "https://api.instagram.com/v1/tags/wkshare/media/recent?access_token=6879280.f59def8.ef48ee03319c4fac8605b38d2436d7db&count=20";
$json_data = json_decode( file_get_contents($url) );
$data = $json_data->data;

$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
if(!$con) die('Could not connect: ' . mysql_error());
mysql_select_db("wkSite", $con);

foreach($data as $data) {
	define(UNIX_TIMESTAMP, date('U'));

	$target = 'upload_img/' . md5(UNIX_TIMESTAMP) . '_' . rand(0, 9999) . '.jpg';
	save_image($data->images->standard_resolution->url, $target);

	$sql="INSERT INTO wkshdisplay (name, content, time, platform, pic, instagram) VALUES ('" .
		addslashes($data->user->full_name) . "', '" .
		addslashes($data->caption->text) . "', " . UNIX_TIMESTAMP . ", 'instagram', '" .
		addslashes($target) . "', '" .
		$data->id . "')";
	if(mysql_query($sql, $con)) {
		//echo "ADDED " . $sql. "<br />";
	} else {
		//echo "FAIL " . $sql . "<br />";
		break;
	}
}

mysql_close($con);
?>
