<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends My_Model
{
  public $_table_name = "menus";
  public $_primary_key = "menu_id";
  public $_order_by = "menu_id";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}