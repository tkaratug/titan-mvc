<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to TITAN</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_image('favicon.ico'); ?>" />
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

	#logo {
		position: relative;
		top: 75px;
		width: 60%;
		margin: 0 auto;
		text-align: center;
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
		color: #666;
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
	<div id="logo">
		<img src="<?php echo get_image('titan.png'); ?>" width="90" />
	</div>
	<div id="container">
		<h3>Welcome to <span class="error_code">TITAN</span></h3>
		<p>This is home page.</p>
	</div>
	<div id="footer">
		<span class="copyright">Developed by <a href="http://www.turankaratug.com" target="_blank">Turan Karatuğ</a></span>
		<span class="version">Version <?php echo VERSION; ?></span>
	</div>
</body>
</html>