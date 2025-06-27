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
    
    // Guru Dashboard Stats
    public function get_guru_stats($guru_id) {
        $stats = array();
        
        // Total kelas diajar (akan diimplementasi nanti)
        $stats['total_kelas'] = 0;
        $stats['total_tugas_aktif'] = 0;
        
        return $stats;
    }
    
    // Siswa Dashboard Stats  
    public function get_siswa_stats($siswa_id) {
        $stats = array();
        
        // Total kelas diikuti (akan diimplementasi nanti)
        $stats['total_kelas'] = 0;
        $stats['tugas_mendatang'] = 0;
        $stats['tugas_terlambat'] = 0;
        
        return $stats;
    }
}
