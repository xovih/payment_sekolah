<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends My_Model
{
  protected $_table_name = "v_login";
  protected $_primary_key = "user_id";
  protected $_order_by = "user_id";
  protected $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}