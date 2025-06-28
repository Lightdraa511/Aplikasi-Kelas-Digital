<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelas_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get kelas with filters and pagination
    public function get_kelas($limit = 10, $offset = 0, $filters = array()) {
        $this->db->select('k.*, u.nama_lengkap as nama_guru, pa.tahun_ajaran, pa.semester');
        $this->db->from('kelas k');
        $this->db->join('users u', 'u.id = k.guru_id');
        $this->db->join('periode_akademik pa', 'pa.id = k.periode_akademik_id');
        
        // Apply filters
        if (!empty($filters['guru_id'])) {
            $this->db->where('k.guru_id', $filters['guru_id']);
        }
        
        if (!empty($filters['periode_id'])) {
            $this->db->where('k.periode_akademik_id', $filters['periode_id']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('k.nama_kelas', $filters['search']);
            $this->db->or_like('k.mata_pelajaran', $filters['search']);
            $this->db->or_like('u.nama_lengkap', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('k.is_active', $filters['is_active']);
        }
        
        $this->db->order_by('k.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    // Count kelas for pagination
    public function count_kelas($filters = array()) {
        $this->db->from('kelas k');
        $this->db->join('users u', 'u.id = k.guru_id');
        
        if (!empty($filters['guru_id'])) {
            $this->db->where('k.guru_id', $filters['guru_id']);
        }
        
        if (!empty($filters['periode_id'])) {
            $this->db->where('k.periode_akademik_id', $filters['periode_id']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('k.nama_kelas', $filters['search']);
            $this->db->or_like('k.mata_pelajaran', $filters['search']);
            $this->db->or_like('u.nama_lengkap', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('k.is_active', $filters['is_active']);
        }
        
        return $this->db->count_all_results();
    }
    
    // Get kelas by ID with details
    public function get_kelas_detail($id) {
        $this->db->select('k.*, u.nama_lengkap as nama_guru, pa.tahun_ajaran, pa.semester');
        $this->db->from('kelas k');
        $this->db->join('users u', 'u.id = k.guru_id');
        $this->db->join('periode_akademik pa', 'pa.id = k.periode_akademik_id');
        $this->db->where('k.id', $id);
        
        return $this->db->get()->row();
    }
    
    // Create new kelas
    public function create_kelas($data) {
        // Get active period
        $this->db->where('is_active', 1);
        $active_period = $this->db->get('periode_akademik')->row();
        
        if (!$active_period) {
            return array('success' => false, 'message' => 'Tidak ada periode akademik aktif');
        }
        
        $kelas_data = array(
            'nama_kelas' => $data['nama_kelas'],
            'mata_pelajaran' => $data['mata_pelajaran'],
            'deskripsi' => $data['deskripsi'],
            'guru_id' => $data['guru_id'],
            'periode_akademik_id' => $active_period->id,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->insert('kelas', $kelas_data)) {
            return array(
                'success' => true,
                'kelas_id' => $this->db->insert_id(),
                'message' => 'Kelas berhasil dibuat'
            );
        }
        
        return array('success' => false, 'message' => 'Gagal membuat kelas');
    }
    
    // Update kelas
    public function update_kelas($id, $data) {
        $kelas_data = array(
            'nama_kelas' => $data['nama_kelas'],
            'mata_pelajaran' => $data['mata_pelajaran'],
            'deskripsi' => $data['deskripsi'],
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        return $this->db->update('kelas', $kelas_data);
    }
    
    // Toggle kelas status
    public function toggle_status($id, $guru_id = null) {
        $this->db->where('id', $id);
        if ($guru_id) {
            $this->db->where('guru_id', $guru_id); // Only guru can toggle their own class
        }
        
        $kelas = $this->db->get('kelas')->row();
        
        if ($kelas) {
            $new_status = $kelas->is_active ? 0 : 1;
            $this->db->where('id', $id);
            if ($guru_id) {
                $this->db->where('guru_id', $guru_id);
            }
            
            return $this->db->update('kelas', array(
                'is_active' => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        
        return false;
    }
    
    // Get students in kelas
    public function get_kelas_students($kelas_id) {
        $this->db->select('u.id, u.nisn_nip, u.nama_lengkap, ks.created_at as joined_at');
        $this->db->from('kelas_siswa ks');
        $this->db->join('users u', 'u.id = ks.siswa_id');
        $this->db->where('ks.kelas_id', $kelas_id);
        $this->db->where('u.is_active', 1);
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result();
    }
    
    // Get available students (not in this kelas)
    public function get_available_students($kelas_id) {
        $this->db->select('u.id, u.nisn_nip, u.nama_lengkap');
        $this->db->from('users u');
        $this->db->where('u.role', 'siswa');
        $this->db->where('u.is_active', 1);
        $this->db->where("u.id NOT IN (
            SELECT siswa_id FROM kelas_siswa WHERE kelas_id = $kelas_id
        )");
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result();
    }
    
    // Add student to kelas
    public function add_student($kelas_id, $siswa_id) {
        // Check if already enrolled
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('siswa_id', $siswa_id);
        $existing = $this->db->get('kelas_siswa')->row();
        
        if ($existing) {
            return array('success' => false, 'message' => 'Siswa sudah terdaftar di kelas ini');
        }
        
        $data = array(
            'kelas_id' => $kelas_id,
            'siswa_id' => $siswa_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->insert('kelas_siswa', $data)) {
            return array('success' => true, 'message' => 'Siswa berhasil ditambahkan ke kelas');
        }
        
        return array('success' => false, 'message' => 'Gagal menambahkan siswa ke kelas');
    }
    
    // Remove student from kelas
    public function remove_student($kelas_id, $siswa_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('siswa_id', $siswa_id);
        
        if ($this->db->delete('kelas_siswa')) {
            return array('success' => true, 'message' => 'Siswa berhasil dihapus dari kelas');
        }
        
        return array('success' => false, 'message' => 'Gagal menghapus siswa dari kelas');
    }
    
    // Get kelas statistics
    public function get_kelas_stats($kelas_id) {
        $stats = array();
        
        // Count students
        $this->db->where('kelas_id', $kelas_id);
        $stats['total_siswa'] = $this->db->count_all_results('kelas_siswa');
        
        // Count tugas (will be 0 for now, ready for Phase 5)
        $this->db->where('kelas_id', $kelas_id);
        $stats['total_tugas'] = $this->db->count_all_results('tugas');
        
        return $stats;
    }
    
    // Get siswa's enrolled kelas
    public function get_siswa_kelas($siswa_id) {
        $this->db->select('k.*, u.nama_lengkap as nama_guru, pa.tahun_ajaran, pa.semester, ks.created_at as joined_at');
        $this->db->from('kelas_siswa ks');
        $this->db->join('kelas k', 'k.id = ks.kelas_id');
        $this->db->join('users u', 'u.id = k.guru_id');
        $this->db->join('periode_akademik pa', 'pa.id = k.periode_akademik_id');
        $this->db->where('ks.siswa_id', $siswa_id);
        $this->db->where('k.is_active', 1);
        $this->db->order_by('k.nama_kelas', 'ASC');
        
        return $this->db->get()->result();
    }
    
    // Check if guru owns kelas
    public function is_guru_kelas($kelas_id, $guru_id) {
        $this->db->where('id', $kelas_id);
        $this->db->where('guru_id', $guru_id);
        return $this->db->get('kelas')->num_rows() > 0;
    }
    
    // Check if siswa enrolled in kelas
    public function is_siswa_enrolled($kelas_id, $siswa_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('siswa_id', $siswa_id);
        return $this->db->get('kelas_siswa')->num_rows() > 0;
    }
}