<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Periode_akademik extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Periode_model');
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'form_validation', 'pagination'));
        
        // Only super admin can access
        require_role('super_admin');
    }
    
    // List all periods
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Periode Akademik';
        
        // Get filter inputs
        $search = $this->input->get('search');
        $per_page = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Pagination config
        $config['base_url'] = base_url('admin/periode');
        $config['total_rows'] = $this->Periode_model->count_periods($search);
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Pagination styling (same as users)
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
        
        // Get periods
        $data['periods'] = $this->Periode_model->get_periods($per_page, $page, $search);
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;
        $data['total_periods'] = $config['total_rows'];
        $data['active_period'] = $this->Periode_model->get_active_period();
        
        // Get stats for each period
        foreach ($data['periods'] as $period) {
            $period->stats = $this->Periode_model->get_period_stats($period->id);
        }
        
        $this->load->view('admin/periode/index', $data);
    }
    
    // Create new period
    public function create() {
        $data['user'] = get_user_data();
        $data['title'] = 'Tambah Periode Akademik';
        $data['year_options'] = $this->Periode_model->generate_year_options();
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'required');
            $this->form_validation->set_rules('semester', 'Semester', 'required|in_list[Ganjil,Genap]');
            
            // Custom validation for unique combination
            $tahun_ajaran = $this->input->post('tahun_ajaran');
            $semester = $this->input->post('semester');
            
            if ($this->Periode_model->check_periode_exists($tahun_ajaran, $semester)) {
                $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'callback_periode_unique');
            }
            
            if ($this->form_validation->run()) {
                $period_data = array(
                    'tahun_ajaran' => $tahun_ajaran,
                    'semester' => $semester
                );
                
                if ($this->Periode_model->create_period($period_data)) {
                    $this->session->set_flashdata('success', 'Periode akademik berhasil ditambahkan!');
                    redirect('admin/periode');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan periode akademik!');
                }
            }
        }
        
        $this->load->view('admin/periode/create', $data);
    }
    
    // Callback for unique periode validation
    public function periode_unique($tahun_ajaran) {
        $semester = $this->input->post('semester');
        if ($this->Periode_model->check_periode_exists($tahun_ajaran, $semester)) {
            $this->form_validation->set_message('periode_unique', 'Kombinasi tahun ajaran dan semester sudah ada!');
            return FALSE;
        }
        return TRUE;
    }
    
    // Edit period
    public function edit($id) {
        $data['user'] = get_user_data();
        $data['title'] = 'Edit Periode Akademik';
        $data['period'] = $this->Periode_model->get_period($id);
        $data['year_options'] = $this->Periode_model->generate_year_options();
        
        if (!$data['period']) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'required');
            $this->form_validation->set_rules('semester', 'Semester', 'required|in_list[Ganjil,Genap]');
            
            // Custom validation for unique combination (exclude current)
            $tahun_ajaran = $this->input->post('tahun_ajaran');
            $semester = $this->input->post('semester');
            
            if ($this->Periode_model->check_periode_exists($tahun_ajaran, $semester, $id)) {
                $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'callback_periode_unique_edit');
            }
            
            if ($this->form_validation->run()) {
                $period_data = array(
                    'tahun_ajaran' => $tahun_ajaran,
                    'semester' => $semester
                );
                
                if ($this->Periode_model->update_period($id, $period_data)) {
                    $this->session->set_flashdata('success', 'Periode akademik berhasil diupdate!');
                    redirect('admin/periode');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate periode akademik!');
                }
            }
        }
        
        $this->load->view('admin/periode/edit', $data);
    }
    
    // Callback for unique periode validation (edit)
    public function periode_unique_edit($tahun_ajaran) {
        $id = $this->uri->segment(4);
        $semester = $this->input->post('semester');
        if ($this->Periode_model->check_periode_exists($tahun_ajaran, $semester, $id)) {
            $this->form_validation->set_message('periode_unique_edit', 'Kombinasi tahun ajaran dan semester sudah ada!');
            return FALSE;
        }
        return TRUE;
    }
    
    // Activate period
    public function activate($id) {
        $period = $this->Periode_model->get_period($id);
        
        if (!$period) {
            $this->session->set_flashdata('error', 'Periode tidak ditemukan!');
            redirect('admin/periode');
        }
        
        if ($this->Periode_model->activate_period($id)) {
            $this->session->set_flashdata('success', 
                'Periode "' . $period->tahun_ajaran . ' - ' . $period->semester . '" berhasil diaktifkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan periode!');
        }
        
        redirect('admin/periode');
    }
    
    // Delete period
    public function delete($id) {
        $result = $this->Periode_model->delete_period($id);
        
        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }
        
        redirect('admin/periode');
    }
}
