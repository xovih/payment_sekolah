<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site
{
	public function is_logged_in()
	{
		$_this = &get_instance();
		$user_session = $_this->session->userdata;
		$myUri = $_this->uri;
		$myController = $myUri->segment(1);
		$myMethode = $myUri->segment(2);

		if ($myController == 'auth') {
			if ($myMethode  == 'login') {
				if (isset($user_session['is_login'])) {
					redirect('dashboard');
				}
			}
		} else {
			if (!isset($user_session['is_login'])) {
				redirect('auth/login');
			}
		}
	}

	public function isPermited($akses, $permisi = array())
	{
		if (!in_array($akses, $permisi)) {
			redirect('dashboard');
		}
	}

	public function isExist($index, $array)
	{
		if (array_key_exists($index, $array)) {
			return true;
		} else {
			return false;
		}
	}

	public function uploadFoto($nama_element = null, $nama_foto = null)
	{

		if ($_FILES[$nama_element]['name'] != "") {
			$_this = &get_instance();

			$config['upload_path']   = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/';
			$config['allowed_types'] = 'jpg|png';
			$config['max_size']      = '3000';
			$config['overwrite']	 = TRUE;
			$config['file_name'] 	 = $nama_foto;
			$_this->load->library('upload', $config);

			if (!$_this->upload->do_upload($nama_element)) {
				$error  = array('error' => $_this->upload->display_errors());
				$result = array('pesan' => 'failed', 'notif' => $error);
				return $result;
			} else {
				$hasil = $_this->upload->data();
				$result = array('pesan' => 'success', 'notif' => 'Berhasil Upload Foto', 'data' => $hasil['file_name']);
				return $result;
			}
		} else {
			$result = array('pesan' => 'failed', 'notif' => array('0' => 'Tidak Ada Foto yang Terpilih'));
			return $result;
		}
	}

	public function importFile($nama_element = null, $nama_file = null)
	{

		if ($_FILES[$nama_element]['name'] != "") {
			$_this = &get_instance();

			$config['upload_path']   = $_SERVER['DOCUMENT_ROOT'] . '/assets/file_orders/';
			$config['allowed_types'] = 'csv|txt';
			$config['max_size']      = '12500';
			$config['overwrite']	 = TRUE;
			$config['file_name'] 	 = $nama_file . ".txt";
			$_this->load->library('upload', $config);

			if (!$_this->upload->do_upload($nama_element)) {
				$error  = array('error' => $_this->upload->display_errors());
				$result = array('success' => false, 'message' => $error);
				return $result;
			} else {
				$hasil = $_this->upload->data();
				$result = array('success' => true, 'message' => 'Upload data telah selesai', 'data' => $hasil['file_name']);
				return $result;
			}
		} else {
			$result = array('success' => false, 'message' => array('0' => 'Tidak Ada File yang Terpilih'));
			return $result;
		}
	}

	public function rupiah($angka)
	{

		$hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
		return $hasil_rupiah;
	}
}
