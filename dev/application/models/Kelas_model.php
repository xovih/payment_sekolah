<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends My_Model
{
  public $_table_name = "kelas";
  public $_primary_key = "id_kelas";
  public $_order_by = "tingkat";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}