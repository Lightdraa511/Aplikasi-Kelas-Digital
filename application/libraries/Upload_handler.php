<?php
// =============================================================================
// 1. CREATE FILE - application/libraries/Upload_handler.php
// =============================================================================
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_handler {
    
    protected $CI;
    protected $upload_path = './uploads/';
    protected $allowed_types = 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip|rar|jpg|png|gif';
    protected $max_size = 10240; // 10MB
    protected $encrypt_name = TRUE;
    
    public function __construct($config = array()) {
        $this->CI =& get_instance();
        $this->CI->load->library('upload');
        
        if (!empty($config)) {
            $this->initialize($config);
        }
    }
    
    public function initialize($config = array()) {
        foreach ($config as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }
    
    public function upload_file($field_name, $subfolder = '') {
        // Set upload path
        $upload_path = $this->upload_path;
        if ($subfolder) {
            $upload_path .= trim($subfolder, '/') . '/';
        }
        
        // Create directory if not exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        // Generate unique filename
        $file_name = '';
        if ($this->encrypt_name) {
            $file_name = uniqid() . '_' . time();
        }
        
        // Upload configuration
        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => $this->allowed_types,
            'max_size' => $this->max_size,
            'encrypt_name' => $this->encrypt_name,
            'file_name' => $file_name
        );
        
        $this->CI->upload->initialize($config);
        
        if ($this->CI->upload->do_upload($field_name)) {
            $upload_data = $this->CI->upload->data();
            return array(
                'success' => true,
                'file_name' => $upload_data['file_name'],
                'file_path' => $upload_data['full_path'],
                'file_size' => $upload_data['file_size'],
                'file_type' => $upload_data['file_type'],
                'upload_data' => $upload_data
            );
        } else {
            return array(
                'success' => false,
                'error' => $this->CI->upload->display_errors('', '')
            );
        }
    }
    
    public function delete_file($file_path) {
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
        return false;
    }
    
    public function get_file_info($file_path) {
        if (file_exists($file_path)) {
            return array(
                'name' => basename($file_path),
                'size' => filesize($file_path),
                'type' => mime_content_type($file_path),
                'modified' => filemtime($file_path)
            );
        }
        return false;
    }
    
    public function validate_file_type($file_name) {
        $allowed_extensions = explode('|', $this->allowed_types);
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        return in_array($file_extension, $allowed_extensions);
    }
    
    public function format_file_size($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}