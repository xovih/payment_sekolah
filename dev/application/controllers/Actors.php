<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Actors extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Actor_model', 'actor');
		$this->load->model('User_model', 'users');
		$this->load->model('Menu_model', 'menus');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'actors', $this->_user_access);
		$this->data['title'] = 'Actors';
		$this->data['content'] = 'actor/index';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$post = $this->input->post(NULL, TRUE);
			switch ($param) {
				case "getactor":
					$id = $post["id"];
					
					$req = $this->actor->get($id);

					if ($req) {
						echo json_encode([
							"success" => true,
							"message" => "Data Ditemukan !",
							"data" => $req
						]);
					} else {
						echo json_encode([
							"success" => true,
							"message" => "Data Kosong !",
							"data" => []
						]);
					}
				break;
				case "getactors":
					$params = null;

					$offset  = null;
					$limit = 10;

					if (!empty($post['page']) && $post['page'] > 1) {
						$offset = ($post['page'] - 1) * $limit;
					}

					if (!empty($post['search'])) {
						$q = $post['search'];
						$params = " role LIKE '%$q%' ";
					}

					$req = $this->actor->get_by($params, $limit, $offset);
					$totalRow = $this->actor->count($params);

					if ($req) {
						$this->menus->_table_name = "v_actor_menus";
						$this->users->_table_name = "v_users";
						$resData = $req;
						$no = (intval($post['page']) - 1 ) * $limit + 1 ;
						foreach ($resData as $data) {
							$data->no = $no;
							$data->menu_count = $this->menus->count(["actor_id" => $data->actor_id]);
							$data->user_count = $this->users->count(["actor_id" => $data->actor_id]);
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
							"data" => [],
							"message" => "Data Kosong !"
						]);
					}
						
				break;
				case "actormenus":
					$this->menus->_table_name = "v_actor_menus";

					$req = $this->menus->get_by(["actor_id" => $post["id"]]);

					if ($req) {
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => $req
							)
						);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data Kosong !"
						]);
					}
				break;
				case "actorusers":
					$this->users->_table_name = "v_users";
					$req = $this->users->get_by(["actor_id" => $post["id"]]);

					if ($req) {
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => $req
							)
						);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data Kosong !"
						]);
					}
				break;
				case "add":
					$req = $this->actor->insert(["role" => $post["name"]]);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Berhasil menambahkan aktor {$post['name']}"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"data" => [],
							"message" => "Internal Server Error !"
						]);
					}
				break;
				case 'copy':
					$cek = $this->actor->count(["role" => trim($post['name'])]);

					if ($cek > 0) {
						echo json_encode([
							'success' => false, 
							'message' => 'Actor : <b>' . trim($post['name']) . '</b> is Already Exist !'
						]);
					} else {
							$data = array(
								"role" => trim($post['name'])
							);

							if ($id = $this->actor->insert($data, FALSE, TRUE)) {
								$kunci = array('actor_id' => $post['actorid']);
								
								$this->actor->_table_name = "actor_details";
								$this->actor->_primary_key = "detail_id";
								$this->actor->_order_by = "detail_id";
								
								$detail = $this->actor->get_by($kunci);
								
								$menu_detail = [];
								foreach ($detail as $mn) {
										$tmp = [
												"actor_id" => $id,
												"menu_id" => $mn->menu_id
										];

										$menu_detail[] = $tmp;
								}

								if ($this->actor->insert($menu_detail, TRUE) == 'sukses') {
									echo json_encode([
										'error' => false, 
										'message' => 'Successfully copy Actor : <b>' . trim($post['name']) . '</b> from <b> '. $post['oldname'].' </b>!'
									]);
								}
							} else {
								echo json_encode([
									'error' => true, 
									'message' => 'Internal Server Error !'
								]);
							}
					}
				break;
				case "allmenus":
					$reqMenu = $this->menus->get();

					if ($reqMenu) {
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => $reqMenu
							)
						);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data kosong !"
						]);
					}
				break;
				case "addmenutoactor":

					$req = [
						"actor_id" => $post['actorid'],
						"menu_id" => $post['menuid'],
					];

					$this->actor->_table_name = "actor_details";
					$this->actor->_primary_key = "detail_id";

					$res = $this->actor->insert($req);

					if ($res == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses menambahkan menu !",
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal Diproses !"
						]);
					}
				break;
				case "delmenufromactor":
					$id = $post["detailid"];

					$this->actor->_table_name = "actor_details";
					$this->actor->_primary_key = "detail_id";

					if ($this->actor->delete($id) == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses menghapus menu dari aktor terpilih !",
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Transaksi Gagal Diproses !"
						]);
					}
				break;				
				case "update":
					$oldname = $post["oldname"];
					$newname = trim($post['name']);

					$data = array(
						"role" => $newname,
					);
					$where = ["actor_id" => $post['actorid']];

					$req = $this->actor->update($data, $where);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses Mengubah Nama Aktor <b>{$oldname}</b> menjadi <b>{$newname}</b> !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Gagal mengubah data !"
						]);
					}
				break;
				case "delete":
					$id = $post["id"];

					$req = $this->actor->delete($id);

					if ($req == "sukses") {
						echo json_encode(
							array(
								"success"			=> true,
								"message"   => "Sukses menghapus aktor terpilih !"
							)
						);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Gagal menghapus aktor !"
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
