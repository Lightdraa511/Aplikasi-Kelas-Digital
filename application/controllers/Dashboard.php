<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->helper(array('url', 'auth'));
        $this->load->library('session');
        
        require_login();
    }
    
    public function index() {
        $data['user'] = get_user_data();
        $role = $data['user']['role'];
        
        switch ($role) {
            case 'super_admin':
                $data['stats'] = $this->Dashboard_model->get_admin_stats();
                $this->load->view('dashboard/admin', $data);
                break;
                
            case 'guru':
                $data['stats'] = $this->Dashboard_model->get_guru_stats($data['user']['user_id']);
                $this->load->view('dashboard/guru', $data);
                break;
                
            case 'siswa':
                $data['stats'] = $this->Dashboard_model->get_siswa_stats($data['user']['user_id']);
                $this->load->view('dashboard/siswa', $data);
                break;
                
            default:
                show_404();
        }
    }
}
