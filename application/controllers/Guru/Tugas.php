<?php
// =============================================================================
// 2. CREATE CONTROLLER - application/controllers/Guru/Tugas.php
// =============================================================================
defined('BASEPATH') OR exit('No direct script access allowed');
class Tugas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(array('Tugas_model', 'Kelas_model'));
        $this->load->helper(array('url', 'form', 'auth', 'file'));
        $this->load->library(array('session', 'form_validation', 'upload'));
        
        require_role('guru');
    }
    
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Kelola Tugas';
        $guru_id = $data['user']['user_id'];
        
        $search = $this->input->get('search');
        $kelas_filter = $this->input->get('kelas');
        $status_filter = $this->input->get('status');
        $per_page = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        $filters = array('guru_id' => $guru_id);
        if ($search) $filters['search'] = $search;
        if ($kelas_filter) $filters['kelas_id'] = $kelas_filter;
        if ($status_filter) $filters['status'] = $status_filter;
        
        $data['tugas_list'] = $this->Tugas_model->get_tugas($per_page, $page, $filters);
        $data['total_tugas'] = $this->Tugas_model->count_tugas($filters);
        $data['search'] = $search;
        $data['kelas_filter'] = $kelas_filter;
        $data['status_filter'] = $status_filter;
        
        // Get kelas options untuk filter
        $kelas_filters = array('guru_id' => $guru_id, 'is_active' => 1);
        $data['kelas_options'] = $this->Kelas_model->get_kelas(100, 0, $kelas_filters);
        
        $this->load->view('guru/tugas/index', $data);
    }
    
	public function create() {
		$data['user'] = get_user_data();
		$data['title'] = 'Buat Tugas Baru';
		$guru_id = $data['user']['user_id'];
		
		// Get active kelas
		$kelas_filters = array('guru_id' => $guru_id, 'is_active' => 1);
		$data['kelas_options'] = $this->Kelas_model->get_kelas(100, 0, $kelas_filters);
		
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
			$this->form_validation->set_rules('judul', 'Judul Tugas', 'required|trim');
			$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');
			$this->form_validation->set_rules('deadline', 'Deadline', 'required');
			$this->form_validation->set_rules('max_poin', 'Nilai Maksimal', 'required|numeric');
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[draft,published]');
			
			if ($this->form_validation->run()) {
				$tugas_data = array(
					'kelas_id' => $this->input->post('kelas_id'),
					'judul' => $this->input->post('judul'),
					'deskripsi' => $this->input->post('deskripsi'),
					'deadline' => $this->input->post('deadline'),
					'max_poin' => $this->input->post('max_poin'),
					'status' => $this->input->post('status')
				);
				
				// Handle file upload using helper
				if (!empty($_FILES['file_materi']['name'])) {
					$upload_result = upload_materi('file_materi');
					
					if ($upload_result['success']) {
						$tugas_data['file_materi'] = $upload_result['file_name'];
					} else {
						$this->session->set_flashdata('error', 'Error upload: ' . $upload_result['error']);
						$this->load->view('guru/tugas/create', $data);
						return;
					}
				}
				
				if ($this->Tugas_model->create_tugas($tugas_data)) {
					$this->session->set_flashdata('success', 'Tugas berhasil dibuat!');
					redirect('guru/tugas');
				} else {
					$this->session->set_flashdata('error', 'Gagal membuat tugas!');
				}
			}
		}
		
		$this->load->view('guru/tugas/create', $data);
	}
    
    public function detail($id) {
        $data['user'] = get_user_data();
        $guru_id = $data['user']['user_id'];
        
        if (!$this->Tugas_model->is_guru_tugas($id, $guru_id)) {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('guru/tugas');
        }
        
        $data['tugas'] = $this->Tugas_model->get_tugas_detail($id);
        $data['submissions'] = $this->Tugas_model->get_submissions($id);
        $data['title'] = 'Detail Tugas: ' . $data['tugas']->judul;
        
        $this->load->view('guru/tugas/detail', $data);
    }
    
    public function grade($submission_id) {
        $data['user'] = get_user_data();
        $guru_id = $data['user']['user_id'];
        
        if ($this->input->method() === 'post') {
            $nilai = $this->input->post('nilai');
            $feedback = $this->input->post('feedback');
            
            if ($this->Tugas_model->grade_submission($submission_id, $nilai, $feedback)) {
                echo json_encode(array('success' => true, 'message' => 'Nilai berhasil disimpan'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Gagal menyimpan nilai'));
            }
        }
    }

	public function grade_simple() {
		$data['user'] = get_user_data();
		$guru_id = $data['user']['user_id'];
		
		if ($this->input->method() === 'post') {
			error_log('POST: ' . print_r($_POST, true));
			$submission_id = $this->input->post('submission_id');
			$tugas_id = $this->input->post('tugas_id');
			$nilai = $this->input->post('nilai');
			$feedback = $this->input->post('feedback');
			
			// Validate
			if ($submission_id == '' || !is_numeric($submission_id) || !is_numeric($nilai)) {
				$this->session->set_flashdata('error', 'Data tidak lengkap!');
				redirect('guru/tugas/detail/' . $tugas_id);
				return;
			}
			
			// Check ownership
			if (!$this->Tugas_model->is_guru_tugas($tugas_id, $guru_id)) {
				$this->session->set_flashdata('error', 'Akses ditolak!');
				redirect('guru/tugas');
				return;
			}
			
			if ($this->Tugas_model->grade_submission($submission_id, $nilai, $feedback)) {
				$this->session->set_flashdata('success', 'Nilai berhasil disimpan!');
			} else {
				$this->session->set_flashdata('error', 'Gagal menyimpan nilai!');
			}
			
			redirect('guru/tugas/detail/' . $tugas_id);
		}
	}
}