<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bayar_model extends My_Model
{
  public $_table_name = "pembayaran";
  public $_primary_key = "id_pembayaran";
  public $_order_by = "waktu_transaksi";
  public $_order_by_type = "DESC";

  function __construct(){
    parent::__construct();
  }

}