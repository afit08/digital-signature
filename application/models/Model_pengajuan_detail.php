<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_pengajuan_detail extends CI_Model
{
    var $table = 'tbl_pengajuan_detail';
    var $select_column = array('id_pengajuan', 'id_pengesah');
    var $order_column = array(null, 'id_pengajuan', 'id_pengesah', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('id_pengajuan', $_POST['search']['value']);
            $this->db->or_like('id_pengesah', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_pengajuan', 'ASC');
        }
    }

    public function make_datatables()
    {
        $this->make_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getAll()
    {
        return $this->db->get($this->table)->result();
    }

    public function getBy($where)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('tbl_user', "tbl_user.id_user = $this->table.id_pengesah", 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query;
    }

    // public function cekKode()
    // {
    //     $query = $this->db->query("SELECT MAX(id_mhs) as id_mhs from $this->table");
    //     $hasil = $query->row();
    //     return $hasil->id_mhs;
    // }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function edit($where, $data)
    {
        $this->db->where($where);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function delete($where)
    {
        $this->db->where($where);
        $query = $this->db->delete($this->table);
        return $query;
    }
}
