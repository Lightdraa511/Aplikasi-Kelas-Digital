<?php
// =============================================================================
// 3. CREATE CONTROLLER - application/controllers/Siswa/Tugas.php
// =============================================================================
defined('BASEPATH') OR exit('No direct script access allowed');
class Tugas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Tugas_model');
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'upload'));
        
        require_role('siswa');
    }
    
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Tugas Saya';
        $siswa_id = $data['user']['user_id'];
        
        $data['tugas_list'] = $this->Tugas_model->get_siswa_tugas($siswa_id);
        
        $this->load->view('siswa/tugas/index', $data);
    }
    
    public function detail($id) {
        $data['user'] = get_user_data();
        $siswa_id = $data['user']['user_id'];
        
        $data['tugas'] = $this->Tugas_model->get_tugas_detail($id);
        $data['submission'] = $this->Tugas_model->get_submission_detail($id, $siswa_id);
        $data['title'] = 'Detail Tugas: ' . $data['tugas']->judul;
        
        $this->load->view('siswa/tugas/detail', $data);
    }
    
	public function submit($id) {
		$data['user'] = get_user_data();
		$siswa_id = $data['user']['user_id'];
		
		if ($this->input->method() === 'post') {
			$tugas = $this->Tugas_model->get_tugas_detail($id);
			$is_late = (strtotime($tugas->deadline) < time()) ? 1 : 0;
			
			if (!empty($_FILES['file_jawaban']['name'])) {
				// Use helper function for upload
				$upload_result = upload_submission('file_jawaban', $id, $siswa_id);
				
				if ($upload_result['success']) {
					$submission_data = array(
						'tugas_id' => $id,
						'siswa_id' => $siswa_id,
						'file_jawaban' => $upload_result['file_name'],
						'is_late' => $is_late
					);
					
					if ($this->Tugas_model->submit_tugas($submission_data)) {
						$this->session->set_flashdata('success', 'Tugas berhasil dikumpulkan!');
					} else {
						$this->session->set_flashdata('error', 'Gagal mengumpulkan tugas!');
					}
				} else {
					$this->session->set_flashdata('error', 'Error upload: ' . $upload_result['error']);
				}
			} else {
				$this->session->set_flashdata('error', 'Pilih file terlebih dahulu!');
			}
			
			redirect('siswa/tugas/detail/' . $id);
		}
	}
    
    public function download($file) {
        $file_path = './uploads/materi/' . $file;
        
        if (file_exists($file_path)) {
            $this->load->helper('download');
            force_download($file_path, NULL);
        } else {
            show_404();
        }
    }
}