<?php defined('DIRECT') OR exit('No direct script access allowed');

class Theme extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->plugin('asset');
		$this->asset->set_title('Welcome to TITAN');
		$this->asset->set_favicon('favicon.ico');
		$this->asset->set_css('style.css');
		$this->load->view('theme_view');
	}
}

?>