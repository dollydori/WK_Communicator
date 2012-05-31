<html>
<head>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
	function test() {
		$('#upload_remote').submit();
/*
		//alert( document.forms["upload"]["file"].value );
		var ext = $('input[name="file"]').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			alert('Invalid Extension!');
		} else {
			$('#upload_img').submit();
		}
*/
	}
	</script>
</head>
<body>
<!--
<form id="upload_img" action="upload_file.php" method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" />
	<br />
	<input type="button" value="test" onclick="test()" />
</form>
-->
<form id="upload_remote" action="upload_remote.php" method="post">
	<input id="url" name="url" style="width:500px;" type="text" />
	<input type="button" value="test" onclick="test()" />
</form>
</body>
</html>
