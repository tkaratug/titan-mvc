<?php defined('DIRECT') OR exit('No direct script access allowed');

class Dashboard extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('backend/dashboard_view');
	}

}