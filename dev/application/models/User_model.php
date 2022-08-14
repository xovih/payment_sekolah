<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends My_Model
{
  public $_table_name = "users";
  public $_primary_key = "user_id";
  public $_order_by = "user_id";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}