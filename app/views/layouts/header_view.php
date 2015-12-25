<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php
		// Page Title
		if($this->template->get_title())
			echo $this->template->get_title() . "\n";

		// Meta Tags
		if($this->template->get_meta()) {
			foreach($this->template->get_meta() as $meta_tag) {
				echo $meta_tag . "\n";
			}
		}

		// Favicon
		if($this->template->get_favicon())
			echo $this->template->get_favicon() . "\n";

		// Custom CSS Files
		if($this->template->get_css()) {
			foreach($this->template->get_css() as $css_file) {
				echo $css_file . "\n";
			}
		}
		
		// Custom JS Files
		if($this->template->get_js('header')) {
			foreach($this->template->get_js('header') as $js_file) {
				echo $js_file . "\n";
			}
		}
	?>
</head>
<body>