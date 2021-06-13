<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_catatan_kasus extends CI_Model {
    var $table = 'tbl_catatan_kasus';
    var $select_column = array('id_catatan_kasus','id_pelanggaran_catatan_kasus','penyelesaian_catatan_kasus','evaluasi_catatan_kasus','tanggal_catatan_kasus','pihak_catatan_kasus');
    var $order_column = array(null,'id_catatan_kasus','id_pelanggaran_catatan_kasus','penyelesaian_catatan_kasus','evaluasi_catatan_kasus','tanggal_catatan_kasus','pihak_catatan_kasus',null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('id_pelanggaran_catatan_kasus',$_POST['search']['value']);
            $this->db->or_like('penyelesaian_catatan_kasus',$_POST['search']['value']);
            $this->db->or_like('evaluasi_catatan_kasus',$_POST['search']['value']);
            $this->db->or_like('tanggal_catatan_kasus',$_POST['search']['value']);
            $this->db->or_like('pihak_catatan_kasus',$_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('id_catatan_kasus','DESC');
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

    public function getAllOrder($data)
    {
        $query = $this->db->get($this->table);
        $this->db->order_by($data);
        return $query;
    }

    public function getById($id)
    {
        $this->db->where('id_catatan_kasus',$id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getByTanggal($bulan,$tahun)
    {
        if ($bulan != 0 && $tahun !=0) {
            $this->db->where('MONTH(tanggal_catatan_kasus)',$bulan);
            $this->db->where('YEAR(tanggal_catatan_kasus)',$tahun);
        }else if($bulan != 0 && $tahun == 0 ){
            $this->db->where('MONTH(tanggal_catatan_kasus)',$bulan);
        }else if($bulan == 0 && $tahun != 0 ){
            $this->db->where('YEAR(tanggal_catatan_kasus)',$tahun);
        }
        $query = $this->db->get($this->table);
        return $query;
    }

    public function tambahCatatan($data)
    {
        $query = $this->db->insert($this->table,$data);
        return $query;
    }

    public function editCatatan($data,$id)
    {
        $this->db->where('id_catatan_kasus',$id);
        $query = $this->db->update($this->table,$data);
        return $query;
    }

    public function deleteCatatan($id)
    {
        $this->db->where('id_catatan_kasus',$id);
        $query = $this->db->delete($this->table);
        return $query;
    }

}
