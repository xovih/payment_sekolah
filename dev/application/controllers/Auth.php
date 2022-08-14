<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	protected $user_detail;
	protected $notif = 'Invalid User !';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('site'));
		$this->site->is_logged_in();
		$this->load->model('Login_model', 'login');
	}

	public function login() {
		// var_dump($this->session->userdata());
		$this->load->view('auth/login');
	}

	public function logout() 
	{
		$this->session->sess_destroy();
		redirect('auth/login');
	}

  public function action($param)
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$post = $this->input->post(NULL, TRUE);
			global $SConfig;
			switch ($param) {
				case "login":

          $result = [
						'error' => true,
						'message' => 'Username & Password Wajib Diisi !'
					];

					if (isset($post['username'])) {
						$params = [
							"username" => $post['username'],
						];
						
						$user_pass = $post['password'];

						$loginData = $this->login->get_by($params, 1, 0, true);

						if ($loginData) {
							if (password_verify($user_pass, $loginData->password)) {
								$login_data = array(
									'user_id' 	=> $loginData->user_id,
									'username' 	=> $loginData->username,
									'fullname' 	=> $loginData->fullname,
									'avatar' 		=> $loginData->photo,
									'is_login'	=> TRUE,
									'actor_id' 	=> $loginData->actor_id,
									'role' 	  	=> $loginData->role,
								);

								$this->session->set_userdata($login_data);

								echo json_encode([
									"success"=> true,
									"message"=> "Login Sukses !",
									"data" => [
										'username' 	=> $loginData->username,
										'fullname' 	=> $loginData->fullname,
										'user_id' 	=> $loginData->user_id,
									]
								]);
							} else {
								echo json_encode([
									"success"=> false,
									"message"=> "Password Salah !"
								]);
							}
						} else {
							echo json_encode(
								[
									"success"=> false,
									"message"=> "Pengguna tidak ditemukan !"
								]
							);
						}
							
					} else {
						echo json_encode([
							'success' => false,
							'message' => 'Username & Password Wajib Diisi !'
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