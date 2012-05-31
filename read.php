<?php
	session_start();
	include_once( 'config.php' );

	function _encode($str) {
		$str = str_replace("\r\n", " ", $str);
		$str = str_replace("\r", " ", $str);
		$str = str_replace("\n", " ", $str);
		$str = trim($str);
		$str = urlencode($str);

		return $str;
	}

	$_limit = isset($_GET[limit]) ? $_GET[limit] : 1;

	$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
	if(!$con) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("wkSite", $con);

	echo '<wk>';

	$sql = "SELECT * FROM wkshdisplay ORDER BY id DESC";
	$result = mysql_query($sql);
	$_limit = mysql_num_rows($result) >= $_limit ? $_limit : mysql_num_rows($result);
	$count = 0;
	while($row = mysql_fetch_array($result)) {
		++$count;
		if($count > $_limit) break;

		$timezone = timezone_open('Asia/Shanghai');
		$date = new DateTime('@'.$row['time'], new DateTimeZone('UTC'));
		$date->setTimezone($timezone);
		$imgurl = strlen(trim($row['pic'])) > 0 ? _encode( 'http://wkshanghai.com/display/' . $row['pic'] ) : '';
		echo '<msg id="' . $row['id'] . '" name="' . _encode($row['name']) . '" timestamp="' . $date->format('Y-m-d g:i:s a') . '" platform="' . $row['platform'] . '" pic="' . $imgurl . '">' . _encode($row['content']) . '</msg>';
	}

	echo '</wk>';

	mysql_close($con);
?>
