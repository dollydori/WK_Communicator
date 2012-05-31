<?php
session_start();
include_once( 'config.php' );

if( isset($_POST[record]) && isset($_POST[curr]) || true) {
	$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
	if(!$con) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("wkSite", $con);

	/**********************************************
	 *          background content start          *
	 **********************************************/
	$sql = "SELECT * FROM wkshdisplay ORDER BY id DESC";
	$result = mysql_query($sql);
	$limit = 100;
	$limit = mysql_num_rows($result) >= $limit ? $limit : mysql_num_rows($result);
	$count = 0;

	echo '<div class="log_bg">';
	while($row = mysql_fetch_array($result)) {
		if(++$count > $limit) break;
		echo '<div>' . htmlspecialchars($row['name']) . ': ' . htmlspecialchars($row['content']) . '</div>';
	}
	echo '</div>';
	/*          background content end          */

	/****************************************
	 *          main content start          *
	 ****************************************/
	$sql = "SELECT min(id) FROM wkshdisplay";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) $min = $row['min(id)'];
	$sql = "SELECT max(id) FROM wkshdisplay";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) $max = $row['max(id)'];

	switch($_POST[record]) {
		case 'last':
			$sql = "SELECT * FROM wkshdisplay WHERE id=" . $max;
			$title = 'last';
			break;
		case 'first':
			$sql = "SELECT * FROM wkshdisplay WHERE id=$min";
			$title = 'first';
			break;
		case 'to_prev':
			$sql = "SELECT * FROM wkshdisplay WHERE id=" . ($_POST[curr]-1);
			if($_POST[curr]-1 == $min) $title = 'first';
			break;
		case 'to_next':
			$sql = "SELECT * FROM wkshdisplay WHERE id=" . ($_POST[curr]+1);
			if($_POST[curr]+1 == $max) $title = 'last';
			break;
		default:
			break;
	}
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)) {
		$timezone = timezone_open('Asia/Shanghai');
		$date = new DateTime('@'.$row['time'], new DateTimeZone('UTC'));
		$date->setTimezone($timezone);

		echo '<div class="name">' . htmlspecialchars($row['name']) . ':</div>';
		echo '<div class="content">' . htmlspecialchars($row['content']) . '</div>';
		if( strlen($row['pic']) > 0 ) echo '<div class="image" title="' . htmlspecialchars('http://wkshanghai.com/display/'.$row['pic']) . '"></div>';
		echo '<div class="footnote">' . $date->format('Y-m-d g:i:s a') . ' via ' . $row['platform'] . '</div>';
		echo '<div class="id" title="' . $title . '">' . $row['id'] . '</div>';
	}
	/*          main content end          */

	mysql_close($con);
}
?>
