<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Periode_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all periods with pagination
    public function get_periods($limit = 10, $offset = 0, $search = '') {
        $this->db->select('*');
        $this->db->from('periode_akademik');
        
        // Search filter
        if (!empty($search)) {
            $this->db->like('tahun_ajaran', $search);
        }
        
        $this->db->order_by('is_active', 'DESC');
        $this->db->order_by('tahun_ajaran', 'DESC');
        $this->db->order_by('semester', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    // Count periods for pagination
    public function count_periods($search = '') {
        $this->db->from('periode_akademik');
        
        if (!empty($search)) {
            $this->db->like('tahun_ajaran', $search);
        }
        
        return $this->db->count_all_results();
    }
    
    // Get period by ID
    public function get_period($id) {
        $this->db->where('id', $id);
        return $this->db->get('periode_akademik')->row();
    }
    
    // Get active period
    public function get_active_period() {
        $this->db->where('is_active', 1);
        return $this->db->get('periode_akademik')->row();
    }
    
    // Check if periode already exists
    public function check_periode_exists($tahun_ajaran, $semester, $exclude_id = null) {
        $this->db->where('tahun_ajaran', $tahun_ajaran);
        $this->db->where('semester', $semester);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('periode_akademik')->num_rows() > 0;
    }
    
    // Create new period
    public function create_period($data) {
        $period_data = array(
            'tahun_ajaran' => $data['tahun_ajaran'],
            'semester' => $data['semester'],
            'is_active' => 0, // Default nonaktif
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert('periode_akademik', $period_data);
    }
    
    // Update period
    public function update_period($id, $data) {
        $period_data = array(
            'tahun_ajaran' => $data['tahun_ajaran'],
            'semester' => $data['semester'],
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        return $this->db->update('periode_akademik', $period_data);
    }
    
    // Activate period (deactivate others first)
    public function activate_period($id) {
        // Start transaction
        $this->db->trans_start();
        
        // Deactivate all periods first
        $this->db->update('periode_akademik', array('is_active' => 0));
        
        // Activate selected period
        $this->db->where('id', $id);
        $this->db->update('periode_akademik', array(
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ));
        
        // Complete transaction
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    // Delete period (only if not active and no related data)
    public function delete_period($id) {
        $period = $this->get_period($id);
        
        if (!$period) {
            return array('success' => false, 'message' => 'Periode tidak ditemukan');
        }
        
        if ($period->is_active) {
            return array('success' => false, 'message' => 'Tidak dapat menghapus periode aktif');
        }
        
        // Check if period has related classes
        $this->db->where('periode_akademik_id', $id);
        $kelas_count = $this->db->count_all_results('kelas');
        
        if ($kelas_count > 0) {
            return array('success' => false, 'message' => 'Tidak dapat menghapus periode yang memiliki kelas');
        }
        
        // Safe to delete
        $this->db->where('id', $id);
        $result = $this->db->delete('periode_akademik');
        
        if ($result) {
            return array('success' => true, 'message' => 'Periode berhasil dihapus');
        } else {
            return array('success' => false, 'message' => 'Gagal menghapus periode');
        }
    }
    
    // Get period statistics
    public function get_period_stats($period_id = null) {
        if (!$period_id) {
            $active_period = $this->get_active_period();
            if (!$active_period) {
                return array(
                    'total_kelas' => 0,
                    'total_tugas' => 0,
                    'total_siswa' => 0
                );
            }
            $period_id = $active_period->id;
        }
        
        $stats = array();
        
        // Count classes in this period
        $this->db->where('periode_akademik_id', $period_id);
        $this->db->where('is_active', 1);
        $stats['total_kelas'] = $this->db->count_all_results('kelas');
        
        // Count assignments in this period (through classes)
        $this->db->select('COUNT(t.id) as total');
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        $this->db->where('k.periode_akademik_id', $period_id);
        $result = $this->db->get()->row();
        $stats['total_tugas'] = $result ? $result->total : 0;
        
        // Count unique students in this period
        $this->db->select('COUNT(DISTINCT ks.siswa_id) as total');
        $this->db->from('kelas_siswa ks');
        $this->db->join('kelas k', 'k.id = ks.kelas_id');
        $this->db->where('k.periode_akademik_id', $period_id);
        $result = $this->db->get()->row();
        $stats['total_siswa'] = $result ? $result->total : 0;
        
        return $stats;
    }
    
    // Generate academic year options
    public function generate_year_options($years_ahead = 2) {
        $current_year = date('Y');
        $options = array();
        
        // Generate years from 2020 to current + years_ahead
        for ($year = 2020; $year <= ($current_year + $years_ahead); $year++) {
            $academic_year = $year . '/' . ($year + 1);
            $options[$academic_year] = $academic_year;
        }
        
        return $options;
    }
}