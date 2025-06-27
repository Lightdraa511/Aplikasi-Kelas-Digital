<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'form_validation'));
    }
    
    public function index() {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        $this->login();
    }
    
    public function login() {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nisn_nip', 'NISN/NIP', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run()) {
                $nisn_nip = $this->input->post('nisn_nip');
                $password = $this->input->post('password');
                
                $user = $this->Auth_model->login($nisn_nip, $password);
                
                if ($user) {
                    // Set session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'nisn_nip' => $user->nisn_nip,
                        'nama_lengkap' => $user->nama_lengkap,
                        'role' => $user->role,
                        'force_change_password' => $user->force_change_password,
                        'logged_in' => TRUE
                    );
                    
                    $this->session->set_userdata($session_data);
                    
                    // Check if need to change password
                    if ($user->force_change_password) {
                        $this->session->set_flashdata('warning', 'Silakan ubah password default Anda');
                        redirect('change-password');
                    } else {
                        $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $user->nama_lengkap);
                        redirect('dashboard');
                    }
                } else {
                    $this->session->set_flashdata('error', 'NISN/NIP atau password salah!');
                }
            }
        }
        
        $this->load->view('auth/login');
    }
    
    public function change_password() {
        require_login();
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('current_password', 'Password Lama', 'required');
            $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');
            
            if ($this->form_validation->run()) {
                $user_id = get_user_data('user_id');
                $current_password = $this->input->post('current_password');
                $new_password = $this->input->post('new_password');
                
                // Verify current password
                $user = $this->Auth_model->get_user_by_id($user_id);
                if (password_verify($current_password, $user->password)) {
                    if ($this->Auth_model->change_password($user_id, $new_password)) {
                        $this->session->set_userdata('force_change_password', 0);
                        $this->session->set_flashdata('success', 'Password berhasil diubah!');
                        redirect('dashboard');
                    } else {
                        $this->session->set_flashdata('error', 'Gagal mengubah password!');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password lama tidak sesuai!');
                }
            }
        }
        
        $data['user'] = get_user_data();
        $this->load->view('auth/change_password', $data);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout berhasil!');
        redirect('login');
    }
}