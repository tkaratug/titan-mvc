<?php
	// Custom JS Files
	if($this->asset->get_js('footer')) {
		foreach($this->asset->get_js('footer') as $js_file) {
			echo $js_file . "\n";
		}
	}
?>
</body>
</html>