<?php defined('DIRECT') OR exit('No direct script access allowed');

class Theme extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->plugin('template');
		$this->template->set_title('Welcome to Titan');
		$this->template->set_css('style.css');
		$this->load->view('theme_view');
	}
}

?>