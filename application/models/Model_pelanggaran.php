<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_pelanggaran extends CI_Model
{
    var $table = 'tbl_pelanggaran';
    var $select_column = array('id_pelanggaran', 'kode_pelanggaran', 'induk_siswa_pelanggaran', 'kode_jenis_pel_pelanggaran', 'tanggal_pelanggaran', 'keterangan_pelanggaran');
    var $order_column = array(null, 'id_pelanggaran', 'kode_pelanggaran', 'induk_siswa_pelanggaran', 'kode_jenis_pel_pelanggaran', 'tanggal_pelanggaran', 'keterangan_pelanggaran', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('induk_siswa_pelanggaran', $_POST['search']['value']);
            $this->db->or_like('kode_pelanggaran', $_POST['search']['value']);
            $this->db->or_like('kode_jenis_pel_pelanggaran', $_POST['search']['value']);
            $this->db->or_like('tanggal_pelanggaran', $_POST['search']['value']);
            $this->db->or_like('keterangan_pelanggaran', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_pelanggaran', 'DESC');
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

    // public function getByUname($uname)
    // {
    //     $this->db->where('username_akun',$uname);
    //     $query = $this->db->get($this->table);
    //     return $query;
    // }

    public function cekkode()
    {
        $query = $this->db->query("SELECT MAX(kode_pelanggaran) as kode_pelanggaran from tbl_pelanggaran");
        $hasil = $query->row();
        return $hasil->kode_pelanggaran;
    }
    public function getAll()
    {
        return $this->db->get($this->table);
    }
    public function getAllByStatus($status)
    {
        $this->db->where('status_pelanggaran', $status);
        return $this->db->get($this->table);
    }

    public function getByInduk($induk)
    {
        $this->db->where('induk_siswa_pelanggaran', $induk);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getDashsiswa($induk)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('tbl_jenis_pelanggaran', "tbl_jenis_pelanggaran.kode_jenis_pelanggaran = $this->table.kode_jenis_pel_pelanggaran", 'left');
        $this->db->where('induk_siswa_pelanggaran', $induk);
        return $this->db->get()->result();
    }
    public function getAllByInduk($induk)
    {
        $this->db->where('induk_siswa_pelanggaran', $induk);
        $query = $this->db->get($this->table);
        return $query;
    }

    public function getById($id)
    {
        $this->db->where('id_pelanggaran', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahPelanggaran($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function editPelanggaran($data, $id)
    {
        $this->db->where('id_pelanggaran', $id);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function deletePelanggaran($id)
    {
        $this->db->where('id_pelanggaran', $id);
        $query = $this->db->delete($this->table);
        return $query;
    }
}
