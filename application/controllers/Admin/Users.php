<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'form_validation', 'pagination'));
        
        // Only super admin can access
        require_role('super_admin');
    }
    
    // List all users with pagination and filters
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Kelola Pengguna';
        
        // Get filter inputs
        $search = $this->input->get('search');
        $role_filter = $this->input->get('role');
        $per_page = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Pagination config
        $config['base_url'] = base_url('admin/users');
        $config['total_rows'] = $this->User_model->count_users($search, $role_filter);
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Pagination styling
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
        
        // Get users
        $data['users'] = $this->User_model->get_users($per_page, $page, $search, $role_filter);
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;
        $data['role_filter'] = $role_filter;
        $data['total_users'] = $config['total_rows'];
        
        $this->load->view('admin/users/index', $data);
    }
    
    // Create new user
    public function create() {
        $data['user'] = get_user_data();
        $data['title'] = 'Tambah Pengguna';
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nisn_nip', 'NISN/NIP', 'required|trim|is_unique[users.nisn_nip]');
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[super_admin,guru,siswa]');
            
            if ($this->form_validation->run()) {
                $user_data = array(
                    'nisn_nip' => $this->input->post('nisn_nip'),
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'role' => $this->input->post('role')
                );
                
                $result = $this->User_model->create_user($user_data);
                
                if ($result['success']) {
                    $this->session->set_flashdata('success', 
                        'Pengguna berhasil ditambahkan! Password default: <strong>' . $result['default_password'] . '</strong>');
                    redirect('admin/users');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan pengguna!');
                }
            }
        }
        
        $this->load->view('admin/users/create', $data);
    }
    
    // Edit user
    public function edit($id) {
        $data['user'] = get_user_data();
        $data['title'] = 'Edit Pengguna';
        $data['edit_user'] = $this->User_model->get_user($id);
        
        if (!$data['edit_user']) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nisn_nip', 'NISN/NIP', 'required|trim');
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[super_admin,guru,siswa]');
            
            // Check NISN/NIP uniqueness (exclude current user)
            if ($this->User_model->check_nisn_nip_exists($this->input->post('nisn_nip'), $id)) {
                $this->form_validation->set_rules('nisn_nip', 'NISN/NIP', 'required|trim|callback_nisn_nip_check');
            }
            
            if ($this->form_validation->run()) {
                $user_data = array(
                    'nisn_nip' => $this->input->post('nisn_nip'),
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'role' => $this->input->post('role')
                );
                
                if ($this->User_model->update_user($id, $user_data)) {
                    $this->session->set_flashdata('success', 'Pengguna berhasil diupdate!');
                    redirect('admin/users');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate pengguna!');
                }
            }
        }
        
        $this->load->view('admin/users/edit', $data);
    }
    
    // Callback for NISN/NIP uniqueness check
    public function nisn_nip_check($nisn_nip) {
        $id = $this->uri->segment(4); // Get ID from URL
        if ($this->User_model->check_nisn_nip_exists($nisn_nip, $id)) {
            $this->form_validation->set_message('nisn_nip_check', 'NISN/NIP sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
    
    // Toggle user status
    public function toggle_status($id) {
        if ($this->User_model->toggle_status($id)) {
            $this->session->set_flashdata('success', 'Status pengguna berhasil diubah!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status pengguna!');
        }
        redirect('admin/users');
    }
    
    // Reset password
    public function reset_password($id) {
        $result = $this->User_model->reset_password($id);
        
        if ($result['success']) {
            $this->session->set_flashdata('success', 
                'Password berhasil direset! Password baru: <strong>' . $result['default_password'] . '</strong>');
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset password!');
        }
        
        redirect('admin/users');
    }
    
    // Delete user (soft delete)
    public function delete($id) {
        $user = $this->User_model->get_user($id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan!');
            redirect('admin/users');
        }
        
        // Prevent deleting own account
        if ($user->id == get_user_data('user_id')) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri!');
            redirect('admin/users');
        }
        
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna!');
        }
        
        redirect('admin/users');
    }
}
