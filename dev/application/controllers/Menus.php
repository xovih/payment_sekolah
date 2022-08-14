<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menus extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model', 'menus');
	}

	public function index()
	{
		$this->site->isPermited(base_url() . 'menus', $this->_user_access);
		$this->data['title'] = 'List Menu';
		$this->data['content'] = 'menu/index';
		$this->load->view('halaman', $this->data);
	}

	public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;
			$post = $this->input->post(NULL, TRUE);
			switch ($param) {
				case "getmenus" :
					$params = $post['menuType'] == 'parent' || $post['menuType'] == 'single' ? "NOT(type) = 'child'" : "type = 'child'";
					
					$offset  = null;
					$limit = 10;

					if (!empty($post['page']) && $post['page'] > 1) {
						$offset = ($post['page'] - 1) * $limit;
					}

					if (!empty($post['search'])) {
						$q = $post['search'];
						$params .= " AND (link LIKE '%$q%' OR label LIKE '%$q%')";
					}

					$reqMenu  = $this->menus->get_by($params, $limit, $offset);
					$totalRow = $this->menus->count($params);

					if ($reqMenu) {
						$no = (intval($post['page']) - 1 ) * $limit + 1 ;
						foreach ($reqMenu as $data) {
							$data->no = $no;
							if ($data->type != 'child') $data->child_count = $this->menus->count(["type" => "child", "parent_id" => $data->menu_id]);
							if ($data->type == 'child') $data->parent_name = $this->menus->get_by(["menu_id" => $data->parent_id], 1, null, true)->label;

							$no++;
						}
						echo json_encode(
							array(
								"success"			=> true,
								"data"      => [
									"data"  		=> $reqMenu,
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

				case "getparents" :
					$reqData = $this->menus->get_by(["NOT(type)" => "child"]);
					if ($reqData) {
						echo json_encode([
							"success" => true,
							"data" => $reqData
						]);
					} else {
						echo json_encode([
							"success" => true,
							"data" => [],
							"message" => "Data Kosong !"
						]);
					}
				break;

				case "getmenu" :
					$reqData = $this->menus->get($post["id"]);
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
				
				case "add" :
					$title 			 = $post["title"];
					$link 			 = $post["link"];
					$orderno 		 = $post["orderno"];
					$menutype 	 = $post["menutype"];
					$activeclass = $post["activeclass"];
					$icon 			 = $post["icon"];

					$data = [
						"link" => $link,
						"active_class" => $activeclass,
						"icon" => $icon,
						"label" => $title,
						"type" => $menutype,
						"order_no" => $orderno,
					];

					if (!empty($post["category"])) $data["category"] = $post["category"];
					if (!empty($post["parentid"])) $data["parent_id"] = $post["parentid"];

					$addMenu = $this->menus->insert($data);
					if ($addMenu == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Successfully insert a menu !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
					}
				break;

				case "delete" :
					$menuid = $post["menuid"];
					$name = $post["name"];

					$delMenu = $this->menus->delete($menuid);

					if ($delMenu == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Successfully delete menu {$name} !"
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !"
						]);
					}
				break;

				case "update" :
					$menuid			 = $post["menuid"];
					$title 			 = $post["title"];
					$link 			 = $post["link"];
					$orderno 		 = $post["orderno"];
					$menutype 	 = $post["menutype"];
					$activeclass = $post["activeclass"];
					$icon 			 = $post["icon"];

					$data = [
						"link" => $link,
						"active_class" => $activeclass,
						"icon" => $icon,
						"label" => $title,
						"type" => $menutype,
						"order_no" => $orderno,
					];

					if (!empty($post["category"])) $data["category"] = $post["category"];
					if (!empty($post["parentid"])) $data["parent_id"] = $post["parentid"];

					$editMenu = $this->menus->update($data, ["menu_id" => $menuid]);

					if ($editMenu == "sukses") {
						echo json_encode([
							"success" => true,
							"message" => "Successfully Update menu Terpilih !",
						]);
					} else {
						echo json_encode([
							"success" => false,
							"message" => "Internal Server Error !",
						]);
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
