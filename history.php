<?php
	session_start();
	include_once( 'config.php' );
?>
<html>
<head>
	<title>W+K Communicator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" id="viewport" content="width=320" />
	<style tyle="text/css">
		body {
			font-family: sans-serif;
			font-size: 16px;
			font-weight: bold;
			color: black;
			line-height: 24px;
		}

		#center {
			width: 320px;
			margin: auto;
		}

		h1 {
			font-size: 32px;
			text-align: center;
		}

		hr {
			border: 0;
			border-bottom: 5px dotted black;
			margin: 25px 0;
		}

		.note {
			color: #999;
		}

		.label {
			width: 180px;
			margin-top: 10px;
		}

		form {
		}

		input, textarea {
			display: block;
			border: 2px solid black;
			margin: 0;
			font-size: 16px;
			font-weight: bold;
			width: 316px;
		}

		input[name="name"] {
			padding: 0;
			height: 48px;
		}

		textarea[name="content"] {
			height: 116px;
		}

		input[type="submit"], #btn_pic {
			border: 1px solid #999;
			background-color: black;
			color: white;
			font-size: 20px;
			margin: 10px 0;
			cursor: pointer;
			width: 100%;
		}

		input[type="submit"] {
			height: 3em;
		}

		#btn_pic {
			background-color: #999;
			cursor: auto;
			height: 1.8em;
		}

		.history_msg {
			font-weight: normal;
			font-size: 16px;
			padding: 5px 0;
			width: 100%;
			word-wrap: break-word;
		}

		.history_bg {
			background-color: #EEE;
		}

		.timestamp {
			font-size: 12px;
			color: #999;
		}

		.link_image {
			text-decoration: underline;
			color: #999;
			font-size: 13px;
			font-weight: bold;
		}

		.link_image:hover {
			color: black;
		}

		.or {
			height: 28px;
			font-size: 14px;
		}

		#upload_ui {
			background-color: #EEE;
			padding: 10px 0;
			margin-top: -10px;
		}

		#upload_ui input {
			font-weight: normal;
		}

		#upload_ui #file {
			border: 0;
			margin-bottom: 20px;
		}

		#upload_ui #url {
		}
	</style>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		var upload_file_ui = null;
		var upload_url_ui = null;

		function validateForm() {
			var _name=document.forms["communicator"]["name"].value;
			var _content=document.forms["communicator"]["content"].value;
			if (_name==null || _name=="" || _content==null || _content=="") {
				alert("Name and message must be filled out!");
				return false;
			} else {
				if( $.trim($('#file').val()).length == 0 ) upload_file_ui = $('#file').detach();
				if( $.trim($('#url').val()).length == 0 || $.trim($('#file').val()).length > 0 ) upload_url_ui = $('#url').detach();
				return true;
			}
		}

		function showUploadUI() {
			if( $('#upload_ui').is(':hidden') ) $('#upload_ui').slideDown('fast');
			else $('#upload_ui').slideUp('fast');
		}

		$(document).ready(function() {
			$('#upload_ui').hide();
		});
	</script>
</head>
<body>
<div id="center">
	<?php
	$con = mysql_connect($WKHOST, $WKID, $WKPASSWD);
	if(!$con) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("wkSite", $con);

	$sql = "SELECT * FROM wkshdisplay ORDER BY id DESC";
	$result = mysql_query($sql);
	$limit = 100;
	$limit = mysql_num_rows($result) >= $limit ? $limit : mysql_num_rows($result);
	$count = 0;
	while($row = mysql_fetch_array($result)) {
		++$count;
		if($count > $limit) break;
		$class = $count%2 == 0 ? 'history_msg' : 'history_msg history_bg';

		$timezone = timezone_open('Asia/Shanghai');
		$date = new DateTime('@'.$row['time'], new DateTimeZone('UTC'));
		$date->setTimezone($timezone);
		$imghtml = strlen($row['pic']) > 0 ? ' <a class="link_image" href="http://wkshanghai.com/display/' . urldecode($row['pic']) . '">image</a>' : '';
		echo '<div class="' . $class . '">' . htmlspecialchars(urldecode($row['name'])) . ': ' . htmlspecialchars(urldecode($row['content'])) .
			$imghtml . 
			' <div class="timestamp">[' . $date->format('Y-m-d g:i:s a') . ' from ' . $row['platform'] . ']</div>' .
			' <div class="timestamp">' . $row['ip'] . '</div>' .
			'</div>';
	}

	mysql_close($con);
	?>
</div>
</body>
</html>
