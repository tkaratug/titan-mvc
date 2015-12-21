<?php
	// Custom JS Files
	if($this->template->get_js('footer')) {
		foreach($this->template->get_js('footer') as $js_file) {
			echo $js_file . "\n";
		}
	}
?>
</body>
</html>