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
		
		// Total tugas aktif
		$this->db->select('COUNT(t.id) as total');
		$this->db->from('tugas t');
		$this->db->join('kelas k', 'k.id = t.kelas_id');
		$this->db->where('k.guru_id', $guru_id);
		$this->db->where('t.status', 'published');
		$result = $this->db->get()->row();
		$stats['total_tugas_aktif'] = $result ? $result->total : 0;
		
		// Submissions pending review
		$this->db->select('COUNT(pt.id) as total');
		$this->db->from('pengumpulan_tugas pt');
		$this->db->join('tugas t', 't.id = pt.tugas_id');
		$this->db->join('kelas k', 'k.id = t.kelas_id');
		$this->db->where('k.guru_id', $guru_id);
		$this->db->where('pt.nilai IS NULL');
		$result = $this->db->get()->row();
		$stats['pending_review'] = $result ? $result->total : 0;
		
		return $stats;
	}
	
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
		
		// Tugas mendatang (deadline belum lewat, belum dikumpulkan)
		$this->db->select('COUNT(t.id) as total');
		$this->db->from('tugas t');
		$this->db->join('kelas k', 'k.id = t.kelas_id');
		$this->db->join('kelas_siswa ks', 'ks.kelas_id = k.id');
		$this->db->where('ks.siswa_id', $siswa_id);
		$this->db->where('t.status', 'published');
		$this->db->where('t.deadline >', date('Y-m-d H:i:s'));
		$this->db->where("t.id NOT IN (
			SELECT tugas_id FROM pengumpulan_tugas WHERE siswa_id = $siswa_id
		)");
		$result = $this->db->get()->row();
		$stats['tugas_mendatang'] = $result ? $result->total : 0;
		
		// Tugas terlambat (deadline lewat, belum dikumpulkan)
		$this->db->select('COUNT(t.id) as total');
		$this->db->from('tugas t');
		$this->db->join('kelas k', 'k.id = t.kelas_id');
		$this->db->join('kelas_siswa ks', 'ks.kelas_id = k.id');
		$this->db->where('ks.siswa_id', $siswa_id);
		$this->db->where('t.status', 'published');
		$this->db->where('t.deadline <', date('Y-m-d H:i:s'));
		$this->db->where("t.id NOT IN (
			SELECT tugas_id FROM pengumpulan_tugas WHERE siswa_id = $siswa_id
		)");
		$result = $this->db->get()->row();
		$stats['tugas_terlambat'] = $result ? $result->total : 0;
		
		// Nilai terbaru
		$this->db->select('pt.nilai, t.judul, t.max_poin');
		$this->db->from('pengumpulan_tugas pt');
		$this->db->join('tugas t', 't.id = pt.tugas_id');
		$this->db->where('pt.siswa_id', $siswa_id);
		$this->db->where('pt.nilai IS NOT NULL');
		$this->db->order_by('pt.graded_at', 'DESC');
		$this->db->limit(1);
		$stats['nilai_terbaru'] = $this->db->get()->row();
		
		return $stats;
	}	
}
