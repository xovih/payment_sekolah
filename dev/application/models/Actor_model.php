<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Actor_model extends My_Model
{
  public $_table_name = "actors";
  public $_primary_key = "actor_id";
  public $_order_by = "actor_id";
  public $_order_by_type = "ASC";

  function __construct(){
    parent::__construct();
  }

}