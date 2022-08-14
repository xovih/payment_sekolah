<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paymentlist extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Payment_model', 'payment');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'paymentlist', $this->_user_access);
		$this->data['title'] = 'Manajemen Akun Pembayaran';
		$this->data['content'] = 'paymentlist/index';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$p = $this->input->post(NULL, TRUE);
      global $SConfig;
			switch ($param) {

        case "list":
					$offset  = null;
					$limit = !empty($p['page']) ? 10 : 1000;

					$params = "is_active = '1' ";

					if (!empty($p['page']) && $p['page'] > 1) {
						$offset = ($p['page'] - 1) * $limit;
					}

					if (!empty($p['search'])) {
						$q = $p['search'];
						$params .= " AND (kode_akun LIKE '%$q%' OR nama_akun LIKE '%$q%') ";
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->payment->get_by($params, $limit, $offset);
					$totalRow = $this->payment->count($params);

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
					$reqData = $this->payment->get($p["id"]);

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
            'kode_akun' => trim($p['kode_akun']),
            'nama_akun' => $p['nama_akun'],
          );

					$cek = $this->payment->count(['kode_akun' => trim($p['kode_akun']), "is_active" => "1"]);
					if ($cek > 0) {
						echo json_encode([
							"success" => false,
							"message" => "Kode Akun {$p['kode_akun']} sudah terdaftar, silahkan Ganti yang Lain !",
						]);
						exit;
					}

					$req = $this->payment->insert($data);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Menambahkan Jenis Payment !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
						exit;
					}
				break;

				case "update":
					$where = [
            "id_akun" => $p["id_akun"],
          ];

					$data = array(
            'kode_akun' => trim($p['kode_akun']),
            'nama_akun' => $p['nama_akun'],
          );

					$cek = $this->payment->count(['kode_akun' => trim($p['kode_akun']), "NOT(id_akun)" => $p["id_akun"]]);
					if ($cek > 0) {
						echo json_encode([
							"success" => false,
							"message" => "Kode Akun {$p['kode_akun']} sudah terdaftar, silahkan Ganti yang Lain !",
						]);
						exit;
					}

					$req = $this->payment->update($data, $where);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Mengubah Jenis Pembayaran !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete":
					$id = $p["id_akun"];

          $req = $this->payment->update(["is_active" => "0"], ["id_akun" => $id]);

          if ($req == "sukses") {
            echo json_encode([
              "success" => true,
              "message" => "Sukses Menghapus Jenis Payment Terpilih!"
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