<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Bayar_model', 'bayar');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'pembayaran/index', $this->_user_access);
		$this->data['title'] = 'Riwayat Pembayaran';
		$this->data['content'] = 'bayar/index';
		$this->load->view('halaman', $this->data);
	}

	public function add()
	{
		$this->site->isPermited(base_url() . 'pembayaran/add', $this->_user_access);
		$this->data['title'] = 'Pembayaran Tagihan';
		$this->data['content'] = 'bayar/add';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$p = $this->input->post(NULL, TRUE);
      global $SConfig;
			switch ($param) {

        case "list":
          $this->bayar->_table_name = "v_pembayaran";

					$offset  = null;
					$limit = !empty($p['page']) ? 10 : 1000;

					$params = null;

					if (!empty($p['page']) && $p['page'] > 1) {
						$offset = ($p['page'] - 1) * $limit;
					}

					if (!empty($p['search'])) {
						$q = $p['search'];
						$params = " no_induk LIKE '%$q%' OR nama_siswa LIKE '%$q%' OR kode_akun LIKE '%$q%' OR nama_akun LIKE '%$q%' OR catatan LIKE '%$q%'";
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->bayar->get_by($params, $limit, $offset);
					$totalRow = $this->bayar->count($params);

					if ($req) {
						$no = ($page - 1 ) * $limit + 1 ;
						foreach ($req as $data) {
							$data->no = $no;
							$no++;
						}
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => [
									"data"  		=> $req,
									"totalrows" => $totalRow,
									"perpage"   => $limit,
									"totalpage" => ceil($totalRow / $limit),
								]
							)
						);
					} else {
						echo json_encode([
							"success" => true,
							"data" => ["data" => []],
							"message" => "Data Kosong !"
						]);
					}
				break;

				case "get":
					$reqData = $this->bayar->get($p["id"]);

					if ($reqData) {
						echo json_encode([
							"success" => true,
							"data" => $reqData
						]);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data tidak ditemukan !"
						]);
					}

				break;

				case "add":
					$data = array(
            'id_tagihan' => $p['id_tagihan'],
            'nominal' => $p['nominal'],
            'id_petugas' => $this->session->userdata("id_user"),
          );

					$req = $this->bayar->insert($data);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Pembayaran Sukses Diproses !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "update":
					$where = [
            "id_pembayaran" => $p["id_pembayaran"],
          ];

					$data = array(
            'nominal' => $p['nominal'],
            'id_petugas' => $this->session->userdata("user_id"),
          );

					$req = $this->bayar->update($data, $where);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Data Pembayaran Sukses Diubah !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete":
					$id = $p["id_pembayaran"];

          $req = $this->bayar->delete($id);
	
          if ($req == "sukses") {
            echo json_encode([
              "success" => true,
              "message" => "Data Pembayaran Sukses Dihapus !"
            ]);
            exit;
          } else {
            echo json_encode([
              "success" => false,
              "message" => "Internal Server Error !",
            ]);
            exit;
          }


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