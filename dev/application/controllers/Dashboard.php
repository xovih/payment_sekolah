<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data['title'] = 'Dashboard';
		$this->data['content'] = 'home/index';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$post = $this->input->post(NULL, TRUE);
			switch ($param) {
				

				default:
					echo json_encode([
						"error" => true,
						"message" => "Request Invalid !"
					]);
			}
		}
	}
}
