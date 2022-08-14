<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $data = array();
    protected $_user_access = [];

    function __construct()
    {
        parent::__construct();

        $this->load->library(array('site'));
        $this->load->helper(array('template_helper', 'user_helper'));
        $this->site->is_logged_in();

        $this->load->model('Actor_model', 'actors');
        $this->actors->_table_name  = "v_actor_menus";
        $this->actors->_primary_key = "detail_id";
        $this->actors->_order_by    = "order_no";

        $actorID       = $this->session->userdata('actor_id');
        $actor_access  = $this->actors->get_by(["actor_id" => $actorID]);
        
        foreach ($actor_access as $user) {
            $this->_user_access[] = base_url() . $user->link;
        }

        $this->actors->_order_by = 'order_no';

        $menu_top = $this->actors->get_by(["actor_id" => $actorID, "category" => "top", "NOT(type)" => "child"]);
        $menu_stg = $this->actors->get_by(["actor_id" => $actorID, "category" => "pengaturan", "NOT(type)" => "child"]);
        $menu_mtr = $this->actors->get_by(["actor_id" => $actorID, "category" => "master", "NOT(type)" => "child"]);
        $menu_trs = $this->actors->get_by(["actor_id" => $actorID, "category" => "transaksi", "NOT(type)" => "child"]);
        $menu_lap = $this->actors->get_by(["actor_id" => $actorID, "category" => "laporan", "NOT(type)" => "child"]);

        $menu_arr = array();

        foreach ($menu_top as $menu) {
            $child = $this->actors->get_by(["actor_id" => $actorID, "category" => "top", "type" => "child", "parent_id" => $menu->menu_id]);
            $menu->child = $child;
            $menu_arr[] = $menu;
        }
        $menu_top = $menu_arr;

        $menu_arr = array();
        foreach ($menu_stg as $menu) {
            $child = $this->actors->get_by(["actor_id" => $actorID, "category" => "pengaturan", "type" => "child", "parent_id" => $menu->menu_id]);
            $menu->child = $child;
            $menu_arr[] = $menu;
        }
        $menu_stg = $menu_arr;

        $menu_arr = array();
        foreach ($menu_mtr as $menu) {
            $child = $this->actors->get_by(["actor_id" => $actorID, "category" => "master", "type" => "child", "parent_id" => $menu->menu_id]);
            $menu->child = $child;
            $menu_arr[] = $menu;
        }
        $menu_mtr = $menu_arr;

        $menu_arr = array();
        foreach ($menu_trs as $menu) {
            $child = $this->actors->get_by(["actor_id" => $actorID, "category" => "transaksi", "type" => "child", "parent_id" => $menu->menu_id]);
            $menu->child = $child;
            $menu_arr[] = $menu;
        }
        $menu_trs = $menu_arr;

        $menu_arr = array();
        foreach ($menu_lap as $menu) {
            $child = $this->actors->get_by(["actor_id" => $actorID, "category" => "laporan", "type" => "child", "parent_id" => $menu->menu_id]);
            $menu->child = $child;
            $menu_arr[] = $menu;
        }
        $menu_lap = $menu_arr;

        $this->menu = array(
            'menu_top' => $menu_top,
            'menu_stg' => $menu_stg,
            'menu_mtr' => $menu_mtr,
            'menu_trs' => $menu_trs,
            'menu_lap' => $menu_lap,
        );

        $this->data['menus'] = $this->menu;
    }
}
