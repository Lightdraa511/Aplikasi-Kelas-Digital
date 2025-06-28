<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Kelas_model');
        $this->load->helper(array('url', 'auth'));
        $this->load->library('session');
        
        require_role('siswa');
    }
    
    // Siswa view - enrolled kelas
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Kelas Saya';
        $siswa_id = $data['user']['user_id'];
        
        $data['kelas_list'] = $this->Kelas_model->get_siswa_kelas($siswa_id);
        
        // Add stats for each kelas
        foreach ($data['kelas_list'] as $kelas) {
            $kelas->stats = $this->Kelas_model->get_kelas_stats($kelas->id);
        }
        
        $this->load->view('siswa/kelas/index', $data);
    }
    
    // View kelas detail
    public function detail($id) {
        $data['user'] = get_user_data();
        $data['title'] = 'Detail Kelas';
        $siswa_id = $data['user']['user_id'];
        
        // Check if siswa enrolled in this kelas
        if (!$this->Kelas_model->is_siswa_enrolled($id, $siswa_id)) {
            $this->session->set_flashdata('error', 'Anda tidak terdaftar di kelas ini!');
            redirect('siswa/kelas');
        }
        
        $data['kelas'] = $this->Kelas_model->get_kelas_detail($id);
        $data['students'] = $this->Kelas_model->get_kelas_students($id);
        $data['stats'] = $this->Kelas_model->get_kelas_stats($id);
        
        $this->load->view('siswa/kelas/detail', $data);
    }
}
