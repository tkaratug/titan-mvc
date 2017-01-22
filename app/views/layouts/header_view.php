<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php
		// Page Title
		if($this->asset->get_title())
			echo $this->asset->get_title() . "\n";

		// Meta Tags
		if($this->asset->get_meta()) {
			foreach($this->asset->get_meta() as $meta_tag) {
				echo $meta_tag . "\n";
			}
		}

		// Favicon
		if($this->asset->get_favicon())
			echo $this->asset->get_favicon() . "\n";

		// Custom CSS Files
		if($this->asset->get_css()) {
			foreach($this->asset->get_css() as $css_file) {
				echo $css_file . "\n";
			}
		}
		
		// Custom JS Files
		if($this->asset->get_js('header')) {
			foreach($this->asset->get_js('header') as $js_file) {
				echo $js_file . "\n";
			}
		}
	?>
</head>
<body>