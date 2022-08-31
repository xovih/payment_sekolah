<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Bayar_model', 'bayar');
		$this->load->model('Tagihandetail_model', 'detailtagihan');
		$this->load->model('User_model', 'petugas');
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

				case "importExcel":
					$inputan = $p["data"];
					$jumlah_masuk = 0;
					$jumlah_data  = count($inputan);

					$this->detailtagihan->_table_name = "v_tagihan_detail";

					foreach ($inputan as $data) {
						foreach ($data as $key => $val) {
							$data[$key] = trim($data[$key]);
						}

						$kd_akun  = $data["kd_akun"];
						$no_induk = $data["nisn"];
						$nominal  = intval($data["nominal"]);
						$tunai    = intval($data["nominal"]);
						$sisa     = 0;
						$catatan  = $data["catatan"];

						if ($tunai == 0) continue;

						$cekTagihan = $this->detailtagihan->get_by(
							[
								"no_induk"  => $no_induk,
								"kode_akun" => $kd_akun,
							],
							1, 0, true
						);

						$id_tagihan = !empty($cekTagihan) ? $cekTagihan->id_detail : false;

						if (!$id_tagihan) continue;

						$jumlah_tagihan  = intval($cekTagihan->nominal);

						$this->bayar->_table_name = "v_pembayaran";
						$cek_dibayar = $this->bayar->sum("jumlah_bayar", ["id_detail" => $id_tagihan]);
						$jumlah_terbayar = intval($cek_dibayar->jumlah_bayar);

						if ($jumlah_terbayar > 0 && $jumlah_terbayar < $jumlah_tagihan) {
							$jumlah_tagihan = $jumlah_tagihan - $jumlah_terbayar;
							$nominal = $jumlah_tagihan;

							if ($jumlah_tagihan < $tunai) {
								$nominal = $jumlah_tagihan;
								$sisa		 = $tunai - $jumlah_tagihan;
							} else {
								$nominal = $tunai;
								$sisa    = 0;
							}
						} else {
							if ($jumlah_terbayar > 0)  {
								continue;
							} else {
								if ($jumlah_tagihan < $tunai) {
									$nominal = $jumlah_tagihan;
									$sisa		 = $tunai - $jumlah_tagihan;
								} else {
									$nominal = $tunai;
									$sisa    = 0;
								}
							}

						}

						$this->bayar->_table_name = "pembayaran";
						$formulir_pembayaran = [
							"id_tagihan" => $id_tagihan,
							"catatan" 	 => $catatan,
							"nominal" 	 => $nominal,
							"tunai" 	   => $nominal + $sisa,
							"sisa" 	 	   => $sisa,
							"id_petugas" => $this->session->userdata("user_id"),
						];

						if ($this->bayar->insert($formulir_pembayaran) == "sukses") {
							$jumlah_masuk++;
						}
					}

					if ($jumlah_masuk > 0) {
						if ($jumlah_data == $jumlah_masuk) {
							echo json_encode([
								"success" => true,
								"warning" => false,
								"message" => "Semua data pembayaran berhasil diupload !"
							]);
						} else {
							echo json_encode([
								"success" => true,
								"warning" => true,
								"message" => "Tidak Semua data pembayaran berhasil diupload !"
							]);
						}
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Tidak ada data pembayaran yang berhasil diupload !"
						]);
					}
        break;

        case "list":
          $this->bayar->_table_name = "v_pembayaran";
					$this->bayar->_order_by = "waktu_transaksi";

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
              $params .= " AND (waktu_transaksi BETWEEN '$awal' AND '$akir') ";
            } else {
              $params = " waktu_transaksi BETWEEN '$awal' AND '$akir'";
            }
          }

					if (!empty($p['id_tagihan'])) {
						$params = ["id_tagihan" => $p["id_tagihan"]];
					}

					$page = !empty($p['page']) ? intval($p['page']) : 1;

					$req = $this->bayar->get_by($params, $limit, $offset);
					$totalRow = $this->bayar->count($params);

					if ($req) {
						$no = ($page - 1 ) * $limit + 1 ;
						foreach ($req as $data) {
							$data->no = $no;
							$no++;

							$bayar = $this->bayar->sum("jumlah_bayar", ["id_detail" => $data->id_detail]);
							$data->terbayar = intval($bayar->jumlah_bayar);
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

					$this->bayar->_table_name = "v_pembayaran";
					$this->bayar->_order_by = "waktu_transaksi";

					$reqData = $this->bayar->get($p["id"]);

					if ($reqData) {

						$id_petugas = $reqData->id_petugas;
						$petugas = $this->petugas->get($id_petugas);
						$reqData->nama_petugas = $petugas->fullname;

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
            'tunai' => $p['tunai'],
            'sisa' => $p['sisa'],
            'catatan' => !empty($p['catatan']) ? $p['catatan'] : "",
            'id_petugas' => $this->session->userdata("user_id"),
          );


					if ($req = $this->bayar->insert($data, false, true)) {
						echo json_encode([
							"success" => true,
							"message" => "Pembayaran Sukses Diproses !",
							"trid" => $req,
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