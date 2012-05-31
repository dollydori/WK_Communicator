<?php
	session_start();
	include_once( 'config.php' );
?>
<html>
<head>
	<title>W+K Communicator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" id="viewport" content="width=320" />
	<link href="style.css" rel="stylesheet" type="text/css" />
	<style tyle="text/css">
	</style>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.waitforimages.js"></script>
	<script type="text/javascript" src="js/jquery.wk.communicator.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			sound = $('#sound')[0];
			intervalKey = window.setInterval(initSlide, 2000);
			initSlide();

			$('#to_prev, #to_next').click(function() {
				toSlide( $(this).attr('id') );
			});

			$(window).resize(onResize);
		});
	</script>
</head>
<body>
	<div id="display"></div>
	<div id="to_prev"></div>
	<div id="to_next"></div>

	<audio id="sound">
		<source src="sound/shipsbell.mp3"></source>
		Your browser isn't invited for super fun audio time.
	</audio>
</body>
</html>
