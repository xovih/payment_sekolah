<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Att_model', 'att');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'attendance', $this->_user_access);
		$this->data['title'] = 'Lap. Kehadiran';
		$this->data['content'] = 'emp/att';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$p = $this->input->post(NULL, TRUE);
      global $SConfig;
			switch ($param) {

        case "attReport":
          $bulan = $p['bulan'];
          $tahun = $p['tahun'];
          $npp = $p['npp'];

          $this->att->_table_name = "absen_{$bulan}{$tahun}";

          $reqAtt = $this->att->get_by([
            "npp" => $npp
          ]);

          echo json_encode([
            "success" => true,
            "data" => $reqAtt
          ]);
        break;

        default:
					echo json_encode([
						"success" => false,
						"message" => "Request Invalid !"
					]);
      }
    }
  }
}