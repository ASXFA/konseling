<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_siswa extends CI_Model {
    var $table = 'tbl_siswa';
    var $select_column = array('id_siswa','induk_siswa','nama_siswa','alamat_siswa','jk_siswa','foto_siswa','poin_siswa','id_kelas_siswa');
    var $order_column = array(null,'id_siswa','induk_siswa','nama_siswa','alamat_siswa','jk_siswa','foto_siswa','poin_siswa','id_kelas_siswa',null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('induk_siswa',$_POST['search']['value']);
            $this->db->or_like('nama_siswa',$_POST['search']['value']);
            $this->db->or_like('alamat_siswa',$_POST['search']['value']);
            $this->db->or_like('jk_siswa',$_POST['search']['value']);
            $this->db->or_like('id_kelas_siswa',$_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('id_siswa','DESC');
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

    // public function getByUname($uname)
    // {
    //     $this->db->where('username_akun',$uname);
    //     $query = $this->db->get($this->table);
    //     return $query;
    // }

    public function getAll()
    {
        return $this->db->get($this->table);
    }

    public function getByInduk($induk)
    {
        $this->db->where('induk_siswa',$induk);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahSiswa($data)
    {
        $query = $this->db->insert($this->table,$data);
        return $query;
    }

    public function editSiswa($data,$id)
    {
        $this->db->where('id_siswa',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function editByIndukSiswa($data,$id)
    {
        $this->db->where('induk_siswa',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function deleteSiswa($id)
    {
        $this->db->where('induk_siswa',$id);
        $query = $this->db->delete($this->table);
        return $query;
    }

}
