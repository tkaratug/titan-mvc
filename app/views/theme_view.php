<?php $this->load->view('layouts/header_view'); ?>

	<div id="logo">
		<img src="<?php echo get_image('titan.png'); ?>" width="90" />
	</div>
	<div id="container">
		<h3>Welcome to <span class="error_code">TITAN</span></h3>
		<p>This is theme page.</p>
	</div>
	<div id="footer">
		<span class="copyright">Developed by <a href="http://www.turankaratug.com" target="_blank">Turan Karatuğ</a></span>
		<span class="version">Version <?php echo VERSION; ?></span>
	</div>

<?php $this->load->view('layouts/footer_view'); ?>