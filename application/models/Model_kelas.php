<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_kelas extends CI_Model {
    var $table = 'tbl_kelas';
    var $select_column = array('id_kelas','kode_kelas','nama_kelas','keterangan_kelas','id_guru_kelas');
    var $order_column = array(null,'id_kelas','kode_kelas','nama_kelas','keterangan_kelas','id_guru_kelas',null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('kode_kelas',$_POST['search']['value']);
            $this->db->or_like('nama_kelas',$_POST['search']['value']);
            $this->db->or_like('keterangan_kelas',$_POST['search']['value']);
            $this->db->or_like('id_guru_kelas',$_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('id_kelas','DESC');
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

    public function getById($id)
    {
        $this->db->where('id_kelas',$id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getByIdGuru($id_guru)
    {
        $this->db->where('id_guru_kelas',$id_guru);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahKelas($data)
    {
        $query = $this->db->insert($this->table,$data);
        return $query;
    }

    public function editKelas($data,$id)
    {
        $this->db->where('id_kelas',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function deleteKelas($id)
    {
        $this->db->where('id_kelas',$id);
        $query = $this->db->delete($this->table);
        return $query;
    }

}
