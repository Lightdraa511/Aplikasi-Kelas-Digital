<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
	public function get_admin_stats() {
		$stats = array();
		
		// Total users by role
		$this->db->select('role, COUNT(*) as total');
		$this->db->from('users');
		$this->db->where('is_active', 1);
		$this->db->group_by('role');
		$users_by_role = $this->db->get()->result();
		
		foreach ($users_by_role as $user) {
			$stats['total_' . $user->role] = $user->total;
		}
		
		// Set default if not exists
		$stats['total_super_admin'] = isset($stats['total_super_admin']) ? $stats['total_super_admin'] : 0;
		$stats['total_guru'] = isset($stats['total_guru']) ? $stats['total_guru'] : 0;
		$stats['total_siswa'] = isset($stats['total_siswa']) ? $stats['total_siswa'] : 0;
		
		// Add periode info
		$this->db->where('is_active', 1);
		$active_period = $this->db->get('periode_akademik')->row();
		$stats['active_period'] = $active_period;
		
		// Total periods
		$stats['total_periods'] = $this->db->count_all('periode_akademik');
		
		return $stats;
	}
    
	public function get_guru_stats($guru_id) {
		$stats = array();
		
		// Total kelas diajar
		$this->db->where('guru_id', $guru_id);
		$this->db->where('is_active', 1);
		$stats['total_kelas'] = $this->db->count_all_results('kelas');
		
		// Total siswa (unique students across all classes)
		$this->db->select('COUNT(DISTINCT ks.siswa_id) as total');
		$this->db->from('kelas_siswa ks');
		$this->db->join('kelas k', 'k.id = ks.kelas_id');
		$this->db->where('k.guru_id', $guru_id);
		$this->db->where('k.is_active', 1);
		$result = $this->db->get()->row();
		$stats['total_siswa'] = $result ? $result->total : 0;
		
		// Total tugas aktif (akan digunakan di Phase 5)
		$stats['total_tugas_aktif'] = 0;
		
		return $stats;
	}
	
	// UPDATE method get_siswa_stats():
	public function get_siswa_stats($siswa_id) {
		$stats = array();
		
		// Total kelas diikuti
		$this->db->select('COUNT(ks.kelas_id) as total');
		$this->db->from('kelas_siswa ks');
		$this->db->join('kelas k', 'k.id = ks.kelas_id');
		$this->db->where('ks.siswa_id', $siswa_id);
		$this->db->where('k.is_active', 1);
		$result = $this->db->get()->row();
		$stats['total_kelas'] = $result ? $result->total : 0;
		
		// Tugas statistics (akan digunakan di Phase 5)
		$stats['tugas_mendatang'] = 0;
		$stats['tugas_terlambat'] = 0;
		
		return $stats;
	}
}
