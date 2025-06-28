<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(array('Kelas_model', 'Periode_model'));
        $this->load->helper(array('url', 'form', 'auth'));
        $this->load->library(array('session', 'pagination'));
        
        require_role('super_admin');
    }
    
    // Admin monitoring - view all kelas (read-only)
    public function index() {
        $data['user'] = get_user_data();
        $data['title'] = 'Monitoring Kelas';
        
        // Get filter inputs
        $search = $this->input->get('search');
        $periode_filter = $this->input->get('periode');
        $status_filter = $this->input->get('status');
        $per_page = 15;
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Build filters
        $filters = array();
        if ($search) $filters['search'] = $search;
        if ($periode_filter) $filters['periode_id'] = $periode_filter;
        if ($status_filter !== '') $filters['is_active'] = $status_filter;
        
        // Pagination config
        $config['base_url'] = base_url('admin/kelas');
        $config['total_rows'] = $this->Kelas_model->count_kelas($filters);
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
        
        // Get data
        $data['kelas_list'] = $this->Kelas_model->get_kelas($per_page, $page, $filters);
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;
        $data['periode_filter'] = $periode_filter;
        $data['status_filter'] = $status_filter;
        $data['total_kelas'] = $config['total_rows'];
        
        // Get periode options for filter
        $data['periode_options'] = $this->Periode_model->get_periods(100, 0);
        
        // Add stats for each kelas
        foreach ($data['kelas_list'] as $kelas) {
            $kelas->stats = $this->Kelas_model->get_kelas_stats($kelas->id);
        }
        
        $this->load->view('admin/kelas/index', $data);
    }
}