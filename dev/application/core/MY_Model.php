<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $_table_name;
    protected $_primary_key;
    protected $_order_by;
    protected $_order_by_type;
    protected $_column_order;
    protected $_column_search;
    public $rules;

    function __construct()
    {
        parent::__construct();
    }

    public function get_by_group($cari, $group, $where = null, $limit = null)
    {
        $this->db->select($cari);
        $this->db->group_by($group);
        if ($where) $this->db->where($where);
        if ($limit) $this->db->limit($limit);
        if ($this->_order_by_type) {
            $this->db->order_by($this->_order_by, $this->_order_by_type);
        } else {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->result();
    }

    public function insert($data, $batch = FALSE, $return_id = false)
    {
        if ($batch == TRUE) {
            if ($this->db->insert_batch($this->_table_name, $data)) {
                return 'sukses';
            } else {
                return 'gagal';
            }
        } else {
            $this->db->set($data);
            if ($this->db->insert($this->_table_name)) {
                if ($return_id == true) {
                    return $this->db->insert_id();
                } else {
                    return 'sukses';
                }
            } else {
                return 'gagal';
            }
        }
    }

    public function update($data, $where = array())
    {
        $this->db->set($data);
        $this->db->where($where);
        if ($this->db->update($this->_table_name)) {
            return 'sukses';
        } else {
            return 'gagal';
        }
    }

    public function get($id = NULL, $single = FALSE)
    {
        if ($id != NULL) {
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        } else if ($single == TRUE) {
            $method = 'row';
        } else {
            $method = 'result';
        }

        if ($this->_order_by_type) {
            $this->db->order_by($this->_order_by, $this->_order_by_type);
        } else {
            $this->db->order_by($this->_order_by);
        }

        return $this->db->get($this->_table_name)->$method();
    }

    public function get_by($where = NULL, $limit = NULL, $offset = NULL, $single = FALSE, $select = NULL)
    {
        if ($select != NULL) {
            $this->db->select($select);
        }
        if ($where) {
            $this->db->where($where);
        }
        if (($limit) && ($offset)) {
            $this->db->limit($limit, $offset);
        } else if ($limit) {
            $this->db->limit($limit);
        }

        return $this->get(NULL, $single);
    }

    public function delete($id)
    {
        if (!$id) {
            return FALSE;
        }

        $this->db->where($this->_primary_key, $id);
        if ($this->db->delete($this->_table_name)) {
            return 'sukses';
        } else {
            return 'gagal';
        }
    }

    public function delete_by($where = NULL)
    {
        if ($where) {
            $this->db->where($where);
        }

        if ($this->db->delete($this->_table_name)) {
            return 'sukses';
        } else {
            return 'gagal';
        }
    }

    public function count($where = NULL)
    {
        if ($where) {
            $this->db->where($where);
        }

        $this->db->from($this->_table_name);
        return $this->db->count_all_results();
    }

    public function sum($key, $where = NULL, $limit = NULL)
    {
        if ($where) {
            $this->db->where($where);
        }
        if ($limit) $this->db->limit($limit);

        $this->db->select_sum($key);
        return $this->db->get($this->_table_name)->row();
    }

    public function replace($data, $table = NULL)
    {
        $mytable = $this->_table_name;
        if ($table) $mytable = $table;
        if ($this->db->replace($mytable, $data)) {
            return 'sukses';
        } else {
            return 'gagal';
        }
    }
}
