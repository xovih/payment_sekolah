<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa_model extends My_Model
{
  public $_table_name = "siswa";
  public $_primary_key = "id_siswa";
  public $_order_by = "nama";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}