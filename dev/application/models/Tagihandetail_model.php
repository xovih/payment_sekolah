<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihandetail_model extends My_Model
{
  public $_table_name = "tagihan_detail";
  public $_primary_key = "id_detail";
  public $_order_by = "id_detail";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}