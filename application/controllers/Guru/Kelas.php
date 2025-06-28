<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(array('Kelas_model', 'User_model'));
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'form_validation', 'pagination'));
        
        require_role('guru');
    }
    
    // Guru dashboard - manage their kelas
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Kelola Kelas';
        $guru_id = $data['user']['user_id'];
        
        // Get filter inputs
        $search = $this->input->get('search');
        $status_filter = $this->input->get('status');
        $per_page = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Build filters (only for this guru)
        $filters = array('guru_id' => $guru_id);
        if ($search) $filters['search'] = $search;
        if ($status_filter !== '') $filters['is_active'] = $status_filter;
        
        // Pagination config
        $config['base_url'] = base_url('guru/kelas');
        $config['total_rows'] = $this->Kelas_model->count_kelas($filters);
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Same pagination styling as admin
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        // Get data
        $data['kelas_list'] = $this->Kelas_model->get_kelas($per_page, $page, $filters);
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;
        $data['status_filter'] = $status_filter;
        $data['total_kelas'] = $config['total_rows'];
        
        // Add stats for each kelas
        foreach ($data['kelas_list'] as $kelas) {
            $kelas->stats = $this->Kelas_model->get_kelas_stats($kelas->id);
        }
        
        $this->load->view('guru/kelas/index', $data);
    }
    
    // Create new kelas
    public function create() {
        $data['user'] = get_user_data();
        $data['title'] = 'Buat Kelas Baru';
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('mata_pelajaran', 'Mata Pelajaran', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');
            
            if ($this->form_validation->run()) {
                $kelas_data = array(
                    'nama_kelas' => $this->input->post('nama_kelas'),
                    'mata_pelajaran' => $this->input->post('mata_pelajaran'),
                    'deskripsi' => $this->input->post('deskripsi'),
                    'guru_id' => $data['user']['user_id']
                );
                
                $result = $this->Kelas_model->create_kelas($kelas_data);
                
                if ($result['success']) {
                    $this->session->set_flashdata('success', $result['message']);
                    redirect('guru/kelas');
                } else {
                    $this->session->set_flashdata('error', $result['message']);
                }
            }
        }
        
        $this->load->view('guru/kelas/create', $data);
    }
    
    // Edit kelas
    public function edit($id) {
        $data['user'] = get_user_data();
        $data['title'] = 'Edit Kelas';
        $guru_id = $data['user']['user_id'];
        
        // Check if guru owns this kelas
        if (!$this->Kelas_model->is_guru_kelas($id, $guru_id)) {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('guru/kelas');
        }
        
        $data['kelas'] = $this->Kelas_model->get_kelas_detail($id);
        
        if (!$data['kelas']) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('mata_pelajaran', 'Mata Pelajaran', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');
            
            if ($this->form_validation->run()) {
                $kelas_data = array(
                    'nama_kelas' => $this->input->post('nama_kelas'),
                    'mata_pelajaran' => $this->input->post('mata_pelajaran'),
                    'deskripsi' => $this->input->post('deskripsi')
                );
                
                if ($this->Kelas_model->update_kelas($id, $kelas_data)) {
                    $this->session->set_flashdata('success', 'Kelas berhasil diupdate!');
                    redirect('guru/kelas');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate kelas!');
                }
            }
        }
        
        $this->load->view('guru/kelas/edit', $data);
    }
    
    // Toggle kelas status
    public function toggle_status($id) {
        $guru_id = get_user_data('user_id');
        
        if ($this->Kelas_model->toggle_status($id, $guru_id)) {
            $this->session->set_flashdata('success', 'Status kelas berhasil diubah!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status kelas!');
        }
        
        redirect('guru/kelas');
    }
    
    // Manage students in kelas
    public function manage_students($id) {
        $data['user'] = get_user_data();
        $data['title'] = 'Kelola Siswa';
        $guru_id = $data['user']['user_id'];
        
        // Check if guru owns this kelas
        if (!$this->Kelas_model->is_guru_kelas($id, $guru_id)) {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('guru/kelas');
        }
        
        $data['kelas'] = $this->Kelas_model->get_kelas_detail($id);
        $data['students'] = $this->Kelas_model->get_kelas_students($id);
        $data['available_students'] = $this->Kelas_model->get_available_students($id);
        
        $this->load->view('guru/kelas/manage_students', $data);
    }
    
    // Add student to kelas (AJAX)
    public function add_student() {
        if ($this->input->method() === 'post') {
            $kelas_id = $this->input->post('kelas_id');
            $siswa_id = $this->input->post('siswa_id');
            $guru_id = get_user_data('user_id');
            
            // Check if guru owns this kelas
            if (!$this->Kelas_model->is_guru_kelas($kelas_id, $guru_id)) {
                echo json_encode(array('success' => false, 'message' => 'Akses ditolak!'));
                return;
            }
            
            $result = $this->Kelas_model->add_student($kelas_id, $siswa_id);
            echo json_encode($result);
        }
    }
    
    // Remove student from kelas (AJAX)
    public function remove_student() {
        if ($this->input->method() === 'post') {
            $kelas_id = $this->input->post('kelas_id');
            $siswa_id = $this->input->post('siswa_id');
            $guru_id = get_user_data('user_id');
            
            // Check if guru owns this kelas
            if (!$this->Kelas_model->is_guru_kelas($kelas_id, $guru_id)) {
                echo json_encode(array('success' => false, 'message' => 'Akses ditolak!'));
                return;
            }
            
            $result = $this->Kelas_model->remove_student($kelas_id, $siswa_id);
            echo json_encode($result);
        }
    }
}