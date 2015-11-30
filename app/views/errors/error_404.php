<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Titan Errors</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR; ?>public/images/favicon.ico" />
	<style>
	@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext);
	body {
		font-family: Open Sans, sans-serif;
		font-size: 12px;
	}

	a {
		text-decoration: none;
		color: #bc5858;
	}

	#container {
		position: relative;
		top: 100px;
		width: 60%;
		margin: 0 auto;
		border: 1px solid #ccc;
		border-radius: 3px;
	}

	#container h3 {
		margin: 0;
		padding: 10px;
		font-size: 18px;
		border-bottom: 1px solid #ccc;
	}

	span.error_code {
		color: #bc5858;
	}

	#container p {
		margin: 0;
		padding: 10px;
		font-size: 12px;
	}
	
	#footer {
		position: relative;
		top: 120px;
		width: 60%;
		margin: 0 auto;
		font-size: 11px;
	}

	#footer span.copyright {
		float: left;
	}

	#footer span.version {
		float: right;
	}
	</style>
</head>
<body>
	<div id="container">
		<h3>Error Code: <span class="error_code">404</span></h3>
		<p>Aradığınız sayfa bulunmamaktadır.</p>
	</div>
	<div id="footer">
		<span class="copyright">Developed by <a href="http://www.turankaratug.com" target="_blank">Turan Karatuğ</a></span>
		<span class="version">Version <?php echo VERSION; ?></span>
	</div>
</body>
</html>