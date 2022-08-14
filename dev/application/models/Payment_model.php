<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends My_Model
{
  public $_table_name = "jenis_pembayaran";
  public $_primary_key = "id_akun";
  public $_order_by = "id_akun";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}