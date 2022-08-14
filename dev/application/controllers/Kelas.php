<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Kelas_model', 'kelas');
		$this->load->model('Siswa_model', 'siswa');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'kelas', $this->_user_access);
		$this->data['title'] = 'Manajemen Kelas';
		$this->data['content'] = 'kelas/index';
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

					$params = null;

					if (!empty($p['page']) && $p['page'] > 1) {
						$offset = ($p['page'] - 1) * $limit;
					}

					if (!empty($p['search'])) {
						$q = $p['search'];
						$params = " tingkat LIKE '%$q%' OR label LIKE '%$q%'";
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->kelas->get_by($params, $limit, $offset);
					$totalRow = $this->kelas->count($params);

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
					$reqData = $this->kelas->get($p["id"]);

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
					$label = trim($p['label']);
					$data = array(
            'tingkat' => $p['tingkat'],
            'label' => $label,
          );

					$cek = $this->kelas->count(["tingkat" => $p['tingkat'], "label" => $label]);
					if ($cek > 0) {
						echo json_encode([
							"success" => false,
							"message" => "Data Kelas sudah Ada !",
						]);
						exit;
					}

					$req = $this->kelas->insert($data);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Menambahkan Data Kelas !"
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
            "id_kelas" => $p["id_kelas"],
          ];

					$data = array(
            'tingkat' => $p['tingkat'],
            'label' => $p['label'],
          );

					$req = $this->kelas->update($data, $where);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Mengubah Data Kelas !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete":
					$id = $p["id_kelas"];

					$cek = $this->siswa->count(["id_kelas" => $id]);

					if ($cek == 0) {
						$req = $this->kelas->delete($id);
	
						if ($req == "sukses") {
							echo json_encode([
								"success" => true,
								"message" => "Sukses Menghapus Data Kelas Terpilih!"
							]);
							exit;
						} else {
							echo json_encode([
								"success" => false,
								"message" => "Internal Server Error !",
							]);
							exit;
						}
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Tidak Bisa Menghapus Kelas, Dikarenakan ada Siswa di Kelas ini !",
						]);
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