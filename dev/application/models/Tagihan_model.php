<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan_model extends My_Model
{
  public $_table_name = "tagihan";
  public $_primary_key = "id_tagihan";
  public $_order_by = "waktu_perubahan";
  public $_order_by_type = "DESC";

  function __construct(){
    parent::__construct();
  }

}