<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_guru extends CI_Model {
    var $table = 'tbl_guru';
    var $select_column = array('id_guru','induk_guru','nama_guru','jabatan_guru','telp_guru');
    var $order_column = array(null,'id_guru','induk_guru','nama_guru','jabatan_guru','telp_guru',null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('induk_guru',$_POST['search']['value']);
            $this->db->or_like('nama_guru',$_POST['search']['value']);
            $this->db->or_like('jabatan_guru',$_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('id_guru','DESC');
        }
    }

    public function make_datatables()
    {
        $this->make_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'],$_POST['start']);
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
        $query = $this->db->get($this->table);
        return $query;
    }

    // public function getByUname($uname)
    // {
    //     $this->db->where('username_akun',$uname);
    //     $query = $this->db->get($this->table);
    //     return $query;
    // }

    public function getById($id)
    {
        $this->db->where('id_guru',$id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getByInduk($induk)
    {
        $this->db->where('induk_guru',$induk);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahGuru($data)
    {
        $query = $this->db->insert($this->table,$data);
        return $query;
    }

    public function editGuru($data,$id)
    {
        $this->db->where('id_guru',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }
    
    public function editGuruByInduk($data,$induk)
    {
        $this->db->where('induk_guru',$induk);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function deleteGuru($induk)
    {
        $this->db->where('induk_guru',$induk);
        $query = $this->db->delete($this->table);
        return $query;
    }

}
