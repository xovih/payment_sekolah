<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Reports extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Api_report", "apireport");
		$this->load->model("Api_device", "apidevice");
	}

  public function index() {
    redirect("reports/periodic");
  }

	public function periodic()
	{
		$this->site->isPermited(base_url() . "users", $this->_user_access);
		$this->data["title"] = "Reports";
		$this->data["content"] = "report/periodic";
		$this->load->view("halaman", $this->data);
	}

	public function periodicexport($ss, $st, $et) {
		$st = str_replace("%20", " ", $st);
		$et = str_replace("%20", " ", $et);
		
		$req = $this->apireport->periodic(["s" => $st, "e" => $et, "id" => $ss]);

		if ($req["statusCode"] == 200) {
			$data = [
				"namafile" => "ExportSuhu_".time(),
				"periode" => $st . " s.d " . $et,
				"data" => $req["result"]->data
			];

			$this->load->view("report/periodicexport", $data);
		} else {
			echo json_encode([
				"error" => true,
				"message" => "Internal Server Error !"
			]);
		}
	}


	public function action($param)
	{
		if (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
			$post = $this->input->post(NULL, TRUE);
			switch ($param) {

				case "sensors" :
					$req = $this->apidevice->get(["perpage" => 1000, "s" => "", "p" => 1]);

					if ($req["statusCode"] == 200) {
						echo json_encode($req["result"]->data);
					}
				break;

				default:
					echo json_encode([
						"error" => true,
						"message" => "Request Invalid !"
					]);
			}
		}
	}
}
