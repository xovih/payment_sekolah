<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Kelas_model', 'kelas');
		$this->load->model('Payment_model', 'payment');
		$this->load->model('Tagihan_model', 'tagih');
		$this->load->model('Bayar_model', 'pembayaran');
		$this->load->model('Tagihandetail_model', 'details');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'tagihan/index', $this->_user_access);
		$this->data['title'] = 'Manajemen Tagihan';
		$this->data['content'] = 'tagihan/index';
		$this->load->view('halaman', $this->data);
	}

  public function add()
	{
		$this->site->isPermited(base_url() . 'tagihan/add', $this->_user_access);
		$this->data['title'] = 'Input Tagihan';
		$this->data['content'] = 'tagihan/add';
		$this->load->view('halaman', $this->data);
	}

  public function update($id = null)
	{
		$this->site->isPermited(base_url() . 'tagihan/index', $this->_user_access);
		$this->data['title'] = 'Update Tagihan';
		$this->data['id_tagihan'] = $id;
		$this->data['content'] = 'tagihan/detail';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$p = $this->input->post(NULL, TRUE);
      global $SConfig;
			switch ($param) {

        case "list":
					$this->pembayaran->_table_name = "v_pembayaran";
					$this->pembayaran->_order_by = "waktu_transaksi";

					$this->tagih->_table_name = "v_tagihan_detail";

					$offset  = null;
					$limit = !empty($p['page']) ? 10 : 1000;

					$params = null;

					if (!empty($p['page']) && $p['page'] > 1) {
						$offset = ($p['page'] - 1) * $limit;
					}

					if (!empty($p['search'])) {
						$q = $p['search'];
						$params = " (kode_akun LIKE '%$q%' OR nama_akun LIKE '%$q%' OR catatan LIKE '%$q%') ";
					}

					if (!empty($p['id_siswa'])) {
						if(strlen($params) > 0) {
							$params .= " AND id_siswa = {$p['id_siswa']} ";
						} else {
							$params = " id_siswa = {$p['id_siswa']} ";
						}
					}

					if (!empty($p['id_akun'])) {
						if(strlen($params) > 0) {
							$params .= " AND id_akun = {$p['id_akun']}";
						} else {
							$params = " id_akun = {$p['id_akun']}";
						}
					}

					if (!empty($p['filter_tanggal'])) {
            $awal = $p['awal_filter'];
            $akir = $p['akir_filter'];

            if (!empty($params) || strlen($params) > 0) {
              $params .= " AND (tenggat_waktu BETWEEN '$awal' AND '$akir') ";
            } else {
              $params = " tenggat_waktu BETWEEN '$awal' AND '$akir'";
            }
          }

					if (!empty($p['id_tagihan'])) {
						$params = ["id_tagihan" => $p["id_tagihan"]];
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->tagih->get_by($params, $limit, $offset);
					$totalRow = $this->tagih->count($params);

					if ($req) {
						$no = ($page - 1 ) * $limit + 1 ;
						foreach ($req as $data) {
							$data->no = $no;
							$no++;

							$bayar = $this->pembayaran->sum("jumlah_bayar", ["id_detail" => $data->id_detail]);
							$data->jumlah_bayar = intval($bayar->jumlah_bayar);
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
					$this->pembayaran->_table_name = "v_pembayaran";
					$this->pembayaran->_order_by = "waktu_transaksi";
					
					$this->tagih->_table_name = "v_tagihan_detail";
					$this->tagih->_primary_key = "id_detail";
					$reqData = $this->tagih->get($p["id"]);

					if ($reqData) {
						$bayar = $this->pembayaran->sum("jumlah_bayar", ["id_detail" => $p["id"]]);

						$reqData->terbayar = $bayar->jumlah_bayar == null ? 0 : $bayar->jumlah_bayar ;

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
            'id_akun' => $p['id_akun'],
            'catatan' => $p['catatan'],
            'nominal' => $p['nominal'],
            'tenggat_waktu' => $p['tenggat_waktu'],
            'user_pembuat' => $this->session->userdata("user_id"),
            'user_perubah' => $this->session->userdata("user_id"),
          );

          $details = $p["details"];

					if ($id_tagihan = $this->tagih->insert($data, false, true)) {
            $data_detail = [];
            foreach ($details as $siswa) {
              $tmp = [
                "id_tagihan" => $id_tagihan,
                "id_siswa" => $siswa,
              ];
              $data_detail[] = $tmp;
            }

            if ($this->details->insert($data_detail, true) == "sukses") {
              echo json_encode([
                "success" => true,
                "message" => "Sukses Menambahkan Tagihan !"
              ]);
              exit;
            } else {
              echo json_encode([
                "success" => false,
                "message" => "Gagal Menambahkan Tagihan !",
              ]);
              exit;
            }
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "update":
          if (empty($p["id_tagihan"])) {
            echo json_encode([
              "success" => false,
              "message" => "Belum ada Tagihan Terpilih !",
            ]);
            exit;
          }

          $where = ["id_tagihan" => $p["id_tagihan"]];
					$data = array(
            'id_akun' => $p['id_akun'],
            'catatan' => $p['catatan'],
            'nominal' => $p['nominal'],
            'tenggat_waktu' => $p['tenggat_waktu'],
            'user_pembuat' => $this->session->userdata("user_id"),
            'user_perubah' => $this->session->userdata("user_id"),
          );

          $details = $p["details"];

					if ($this->tagih->update($data, $where) == "sukses") {
            $this->details->delete_by($where);
            $data_detail = [];
            foreach ($details as $detil) {
              $tmp = [
                "id_tagihan" => $p["id_tagihan"],
                "id_siswa" => $detil,
              ];
              $data_detail[] = $tmp;
            }

            if ($this->details->insert($data_detail, true) == "sukses") {
              echo json_encode([
                "success" => true,
                "message" => "Sukses Mengubah Data Tagihan !"
              ]);
              exit;
            } else {
              echo json_encode([
                "success" => false,
                "message" => "Gagal Mengubah Data Tagihan !",
              ]);
              exit;
            }
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete":
					$id = $p["id_tagihan"];

          $req = $this->tagih->delete($id);

          if ($req == "sukses") {
            echo json_encode([
              "success" => true,
              "message" => "Sukses Menghapus Data Tagihan Terpilih!"
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

				case "importExcel":
					$inputan = $p["data"];
					$jumlah_masuk = 0;
					$jumlah_data  = count($inputan);

					foreach ($inputan as $data) {
						foreach ($data as $key => $val) {
							$data[$key] = trim($data[$key]);
						}

						$kelas   = str_split($data["kelas"]);
						$tingkat = $kelas[0];
						$label   = $kelas[1];

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
						} 

						if ($id_kelas == null) {
							echo "$kelas $tingkat";
							continue;
						}

						$siswa = $this->siswa->get_by(["id_kelas" => $id_kelas]);
						if (empty($siswa) || count($siswa) == 0) continue;

						$id_akun = $data["kd_akun"];
						$nm_akun = $data["nm_akun"];
						$cekAkun = $this->payment->get_by(
							[
								"kode_akun" => $id_akun,
								"nama_akun" => $nm_akun,
							],
							1, 0, true
						);

						if (empty($cekAkun)) {
							$id_akun = $this->payment->insert(
								[
									"kode_akun" => $id_akun,
									"nama_akun" => $nm_akun,
								],
								false,
								true
							);
						} else {
							$this->payment->update(
								[
									"kode_akun" => $id_akun,
									"nama_akun" => $nm_akun,
								],
								[
									"id_akun" => $cekAkun->id_akun,
								]
							);
							$id_akun = $cekAkun->id_akun;
						}

						$formulir_tagihan = [
							"id_akun" => $id_akun,
							"catatan" => $data["catatan"],
							"nominal" => $data["nominal"],
							"tenggat_waktu" => strlen($data["tenggat_waktu"]) == 10 ? $data["tenggat_waktu"] : date("Y-m-d"),
							"user_pembuat" => $this->session->userdata("user_id"),
            	"user_perubah" => $this->session->userdata("user_id"),
						];

						if ($id_tagihan = $this->tagih->insert($formulir_tagihan, false, true)) {
							$detail_tagihan = [];
							foreach ($siswa as $murid) {
								$tagihan_siswa = [
									"id_tagihan" => $id_tagihan,
									"id_siswa"	 => $murid->id_siswa,
								];

								$cek_tagihan = [
									"id_akun" 			=> $id_akun,
									"nominal" 			=> $data["nominal"],
									"catatan" 			=> $data["catatan"],
									"tenggat_waktu" => strlen($data["tenggat_waktu"]) == 10 ? $data["tenggat_waktu"] : date("Y-m-d"),
									"user_pembuat" 	=> $this->session->userdata("user_id"),
									"id_siswa" 			=> $murid->id_siswa
								];

								$this->details->_table_name = "v_tagihan_detail";
								$jumlah_tagihan = $this->details->count($cek_tagihan);

								if ($jumlah_tagihan < 1) $detail_tagihan[] = $tagihan_siswa;
							}
							

							if (count($detail_tagihan) == 0) {
								$this->tagih->delete($id_tagihan);
							} else {
								$this->details->_table_name = "tagihan_detail";

								if ($this->details->insert($detail_tagihan, true) == "sukses") {
									$jumlah_masuk++;
								}
							}
						} 
					}

					if ($jumlah_masuk > 0) {
						if ($jumlah_data == $jumlah_masuk) {
							echo json_encode([
								"success" => true,
								"warning" => false,
								"message" => "Semua data tagihan berhasil diupload !"
							]);
						} else {
							echo json_encode([
								"success" => true,
								"warning" => true,
								"message" => "Tidak Semua data tagihan berhasil diupload !"
							]);
						}
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Tidak ada data tagihan yang berhasil diupload !"
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