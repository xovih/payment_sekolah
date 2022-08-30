<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Kelas_model', 'kelas');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'siswa', $this->_user_access);
		$this->data['title'] = 'Data Siswa';
		$this->data['content'] = 'siswa/index';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$p = $this->input->post(NULL, TRUE);
      global $SConfig;
			switch ($param) {

        case "list":
          $this->siswa->_table_name = "v_siswa";

					$offset  = null;
					$limit = !empty($p['page']) ? 10 : 1000;

					$params = null;

					if (!empty($p['page']) && $p['page'] > 1) {
						$offset = ($p['page'] - 1) * $limit;
					}

					if (!empty($p['search'])) {
						$q = $p['search'];
						$params = " (nama LIKE '%$q%' OR no_induk LIKE '%$q%') ";
					}

					if (!empty($p["id_kelas"])) {
						$params = ["id_kelas" => $p["id_kelas"]];
					}

					if(!empty($p["no_induk"])) {
						$no_induk = $p["no_induk"];
						$params = ["no_induk" => $no_induk];
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->siswa->get_by($params, $limit, $offset);
					$totalRow = $this->siswa->count($params);

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
					$this->siswa->_table_name = "v_siswa";
					$reqData = $this->siswa->get($p["id"]);

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
            'no_induk' => $p['no_induk'],
            'nama' => $p['nama'],
            'jenis_kelamin' => $p['gender'],
            'id_kelas' => $p['id_kelas'],
          );

					$getID = $this->siswa->count(['no_induk' => $p['no_induk']]);

					if ($getID > 0) {
						echo json_encode([
							"success" => false,
							"message" => "No Induk sudah terdaftar, silahkan ganti yang lain !",
						]);
						exit;
					}

					$req = $this->siswa->insert($data);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Menambahkan Siswa !"
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
            "id_siswa" => $p["id_siswa"],
          ];

					$data = [
						'no_induk' => $p['no_induk'],
            'nama' => $p['nama'],
            'jenis_kelamin' => $p['gender'],
            'id_kelas' => $p['id_kelas'],
					];

					$req = $this->siswa->update($data, $where);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Mengubah Data Siswa !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete":

					$req = $this->siswa->delete($p["id_siswa"]);

					if ($req == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Sukses Menghapus Data Siswa Terpilih!"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}

				break;

				case "importExcel":
					$inputan = $p["data"];
          $data_siswa = [];

					foreach ($inputan as $data) {

						foreach ($data as $key => $val) {
							$data[$key] = trim($data[$key]);
						}

						$kelas   = str_split($data["kelas"]);
						$tingkat = $kelas[0];
						$label   = $kelas[1];

						$no_induk = $data["nisn"];
						$nama 		= $data["nama"];
						$jenis_kelamin = $data["jk"];

						$cek_kelas = $this->kelas->get_by(
							[
								"tingkat" => $tingkat,
								"label" 	=> $label,
							],
							1,
							0,
							true
						);

						$id_kelas = null;

						if ($cek_kelas) {
							$id_kelas = $cek_kelas->id_kelas;
						} else {
							$id_kelas = $this->kelas->insert(
								[
									"tingkat" => $tingkat,
									"label" 	=> $label,
								],
								false,
								true
							);
						}

						$siswa = [
							"no_induk" => $no_induk,
							"nama" => $nama,
							"jenis_kelamin" => $jenis_kelamin,
							"id_kelas" => $id_kelas,
						];

						$hitung_siswa = $this->siswa->count(
							[
								"no_induk" => $no_induk,
								"nama" => $nama,
								"jenis_kelamin" => $jenis_kelamin
							]
						);

						if ($hitung_siswa == 0) $data_siswa[] = $siswa;
					}

					if (count($data_siswa) > 0) {
						$req = $this->siswa->insert($data_siswa, true);

						if ($req == "sukses") {
							echo json_encode([
								"success" => true,
								"message" => "Sukses Mengimport Data Siswa !"
							]);
						} else {
							echo json_encode([
								"success" => false,
								"message" => "Gagal memproses import !"
							]);
						}
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Tidak ada data siswa yang diupload, kemungkinan semua siswa di excel sudah terdaftar."
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