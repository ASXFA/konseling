<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_jenis_pelanggaran extends CI_Model {
    var $table = 'tbl_jenis_pelanggaran';
    var $select_column = array('id_jenis_pelanggaran','kode_jenis_pelanggaran','nama_jenis_pelanggaran','kategori_jenis_pelanggaran','poin_jenis_pelanggaran');
    var $order_column = array(null,'id_jenis_pelanggaran','kode_jenis_pelanggaran','nama_jenis_pelanggaran','kategori_jenis_pelanggaran','poin_jenis_pelanggaran',null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('kode_jenis_pelanggaran',$_POST['search']['value']);
            $this->db->or_like('nama_jenis_pelanggaran',$_POST['search']['value']);
            $this->db->or_like('kategori_jenis_pelanggaran',$_POST['search']['value']);
            $this->db->or_like('poin_jenis_pelanggaran',$_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('kategori_jenis_pelanggaran','ASC');
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
        $this->db->where('id_jenis_pelanggaran',$id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function cekkode()
    {
        $query = $this->db->query("SELECT MAX(kode_jenis_pelanggaran) as kode_jenis_pelanggaran from tbl_jenis_pelanggaran");
        $hasil = $query->row();
        return $hasil->kode_jenis_pelanggaran;
    }

    public function getByKode($kode)
    {
        $this->db->where('kode_jenis_pelanggaran',$kode);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahJenispelanggaran($data)
    {
        $query = $this->db->insert($this->table,$data);
        return $query;
    }

    public function editJenispelanggaran($data,$id)
    {
        $this->db->where('id_jenis_pelanggaran',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function deleteJenispelanggaran($id)
    {
        $this->db->where('id_jenis_pelanggaran',$id);
        $query = $this->db->delete($this->table);
        return $query;
    }

}
