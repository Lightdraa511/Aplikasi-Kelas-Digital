<?php
// =============================================================================
// PHASE 5: COMPLETE IMPLEMENTATION - TUGAS & FILE SYSTEM
// =============================================================================

// =============================================================================
// 1. CREATE MODEL - application/models/Tugas_model.php
// =============================================================================
defined('BASEPATH') OR exit('No direct script access allowed');
class Tugas_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get tugas dengan filter
    public function get_tugas($limit = 10, $offset = 0, $filters = array()) {
        $this->db->select('t.*, k.nama_kelas, k.mata_pelajaran');
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        
        if (!empty($filters['kelas_id'])) {
            $this->db->where('t.kelas_id', $filters['kelas_id']);
        }
        
        if (!empty($filters['guru_id'])) {
            $this->db->where('k.guru_id', $filters['guru_id']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('t.status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->like('t.judul', $filters['search']);
        }
        
        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    public function count_tugas($filters = array()) {
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        
        if (!empty($filters['kelas_id'])) {
            $this->db->where('t.kelas_id', $filters['kelas_id']);
        }
        
        if (!empty($filters['guru_id'])) {
            $this->db->where('k.guru_id', $filters['guru_id']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('t.status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->like('t.judul', $filters['search']);
        }
        
        return $this->db->count_all_results();
    }
    
    public function get_tugas_detail($id) {
        $this->db->select('t.*, k.nama_kelas, k.mata_pelajaran, k.guru_id');
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        $this->db->where('t.id', $id);
        
        return $this->db->get()->row();
    }
    
    public function create_tugas($data) {
        $tugas_data = array(
            'kelas_id' => $data['kelas_id'],
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'file_materi' => isset($data['file_materi']) ? $data['file_materi'] : null,
            'deadline' => $data['deadline'],
            'max_poin' => $data['max_poin'],
            'status' => $data['status'],
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert('tugas', $tugas_data);
    }
    
    public function update_tugas($id, $data) {
        $tugas_data = array(
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'deadline' => $data['deadline'],
            'max_poin' => $data['max_poin'],
            'status' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if (isset($data['file_materi'])) {
            $tugas_data['file_materi'] = $data['file_materi'];
        }
        
        $this->db->where('id', $id);
        return $this->db->update('tugas', $tugas_data);
    }
    
    // Get submissions for grading
    public function get_submissions($tugas_id) {
        $this->db->select('pt.*, u.nama_lengkap, u.nisn_nip');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('users u', 'u.id = pt.siswa_id');
        $this->db->where('pt.tugas_id', $tugas_id);
        $this->db->order_by('pt.submitted_at', 'ASC');
        
        return $this->db->get()->result();
    }
    
    // Submit assignment
    public function submit_tugas($data) {
        // Check if already submitted
        $this->db->where('tugas_id', $data['tugas_id']);
        $this->db->where('siswa_id', $data['siswa_id']);
        $existing = $this->db->get('pengumpulan_tugas')->row();
        
        if ($existing) {
            // Update existing submission
            $update_data = array(
                'file_previous' => $existing->file_jawaban,
                'file_jawaban' => $data['file_jawaban'],
                'is_late' => $data['is_late'],
                'submitted_at' => date('Y-m-d H:i:s')
            );
            
            $this->db->where('id', $existing->id);
            return $this->db->update('pengumpulan_tugas', $update_data);
        } else {
            // New submission
            $submission_data = array(
                'tugas_id' => $data['tugas_id'],
                'siswa_id' => $data['siswa_id'],
                'file_jawaban' => $data['file_jawaban'],
                'is_late' => $data['is_late'],
                'submitted_at' => date('Y-m-d H:i:s')
            );
            
            return $this->db->insert('pengumpulan_tugas', $submission_data);
        }
    }
    
    // Grade submission
    public function grade_submission($submission_id, $nilai, $feedback) {
        $data = array(
            'nilai' => $nilai,
            'feedback' => $feedback,
            'graded_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $submission_id);
        return $this->db->update('pengumpulan_tugas', $data);
    }
    
    // Get tugas for siswa
    public function get_siswa_tugas($siswa_id) {
        $this->db->select('t.*, k.nama_kelas, k.mata_pelajaran, pt.id as submission_id, pt.nilai, pt.submitted_at, pt.is_late');
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        $this->db->join('kelas_siswa ks', 'ks.kelas_id = k.id');
        $this->db->join('pengumpulan_tugas pt', 'pt.tugas_id = t.id AND pt.siswa_id = ks.siswa_id', 'left');
        $this->db->where('ks.siswa_id', $siswa_id);
        $this->db->where('t.status', 'published');
        $this->db->order_by('t.deadline', 'ASC');
        
        return $this->db->get()->result();
    }
    
    public function get_submission_detail($tugas_id, $siswa_id) {
        $this->db->where('tugas_id', $tugas_id);
        $this->db->where('siswa_id', $siswa_id);
        return $this->db->get('pengumpulan_tugas')->row();
    }
    
    public function is_guru_tugas($tugas_id, $guru_id) {
        $this->db->select('t.id');
        $this->db->from('tugas t');
        $this->db->join('kelas k', 'k.id = t.kelas_id');
        $this->db->where('t.id', $tugas_id);
        $this->db->where('k.guru_id', $guru_id);
        
        return $this->db->get()->num_rows() > 0;
    }
}