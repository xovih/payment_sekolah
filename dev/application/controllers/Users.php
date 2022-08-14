<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
	protected $user_detail;
	protected $notif = 'Belum Login!';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model', 'user');
		$this->load->model('Actor_model', 'actor');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'users', $this->_user_access);
		$this->data['title'] = 'User List';
		$this->data['content'] = 'user/index';
		$this->load->view('halaman', $this->data);
	}

	protected function hashedPassword($str) {
		$options = [
			'cost' => 12
		];
		return password_hash($str, PASSWORD_BCRYPT, $options);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$post = $this->input->post(NULL, TRUE);
			switch ($param) {
				case "getusers":
					$params = null;

					$offset  = null;
					$limit = 10;

					if (!empty($post['page']) && $post['page'] > 1) {
						$offset = ($post['page'] - 1) * $limit;
					}

					if (!empty($post['search'])) {
						$q = $post['search'];
						$params = " fullname LIKE '%$q%' OR username LIKE '%$q%'";
					}

					$this->user->_table_name = "v_users";
					$req = $this->user->get_by($params, $limit, $offset);
					$totalRow = $this->user->count($params);

					if ($req) {
						$no = (intval($post['page']) - 1 ) * $limit + 1 ;
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
							"success" => false,
							"message" => "Internal Server Error !"
						]);
					}
				break;

				case "getuser":
					$id = $post["id"];
					
					$req = $this->user->get($id);

					if ($req) {
						echo json_encode([
							"success" => true,
							"message" => "Request Accepted !",
							"data" => $req
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Data Tidak Ditemukan !",
						]);
					}

				break;

				case "add":
					$data = array(
							'username' => $post['username'],
							'fullname' => $post['fullname'],
							'password' => $this->hashedPassword($post['password']),
							'actor_id' => $post['actor_id'],
							'photo' 	 => $post['photo'],
						);

					$req = $this->user->insert($data);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses menambahkan data pengguna !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal !"
						]);
					}
				break;

				case "update":
					$data = array(
						'fullname' => $post['fullname'],
						'actor_id' => $post['actor_id'],
						'photo' 	 => $post['photo'],
					);
					$where = ['user_id' => $post['user_id']];

					$req = $this->user->update($data, $where);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses Mengubah Datang Pengguna !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal !"
						]);
					}
				break;

				case "delete":
					$where = ["user_id" => $post["user_id"]];
					$data = ["is_active" => false ];

					$req = $this->user->update($data, $where);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses menghapus pengguna terpilih !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi gagal !"
						]);
					}

				break;

				case "activate":
					$where = ["user_id" => $post["user_id"]];
					$data = ["is_active" => true ];

					$req = $this->user->update($data, $where);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses Mengaktifkan Pengguna !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal !"
						]);
					}

				break;

				case "reset":
					$data = array(
						'password' => $this->hashedPassword($post['password']),
					);
					$where = ['user_id' => $post['user_id']];

					$req = $this->user->update($data, $where);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses mengubah Password !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal !"
						]);
					}

				break;

				case "getactors":

					$req = $this->actor->get();

					if ($req) {
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => $req,
							)
						);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data Aktor Kosong !"
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
