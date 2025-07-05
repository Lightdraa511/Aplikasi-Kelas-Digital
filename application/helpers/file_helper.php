<?php
// =============================================================================
// 2. CREATE FILE - application/helpers/file_helper.php  
// =============================================================================
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('upload_materi')) {
    function upload_materi($field_name) {
        $CI =& get_instance();
        $CI->load->library('upload_handler');
        
        $config = array(
            'upload_path' => './uploads/materi/',
            'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx',
            'max_size' => 10240,
            'encrypt_name' => true
        );
        
        $CI->upload_handler->initialize($config);
        return $CI->upload_handler->upload_file($field_name);
    }
}

if (!function_exists('upload_submission')) {
    function upload_submission($field_name, $tugas_id, $siswa_id) {
        $CI =& get_instance();
        $CI->load->library('upload_handler');
        
        $config = array(
            'upload_path' => './uploads/submissions/',
            'allowed_types' => 'pdf|doc|docx|zip|rar',
            'max_size' => 10240,
            'file_name' => 'submission_' . $tugas_id . '_' . $siswa_id . '_' . time()
        );
        
        $CI->upload_handler->initialize($config);
        return $CI->upload_handler->upload_file($field_name);
    }
}

if (!function_exists('delete_uploaded_file')) {
    function delete_uploaded_file($file_path) {
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
        return false;
    }
}

if (!function_exists('get_file_extension')) {
    function get_file_extension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}

if (!function_exists('get_file_icon')) {
    function get_file_icon($filename) {
        $extension = get_file_extension($filename);
        
        $icons = array(
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'ppt' => 'fas fa-file-powerpoint text-warning',
            'pptx' => 'fas fa-file-powerpoint text-warning',
            'xls' => 'fas fa-file-excel text-success',
            'xlsx' => 'fas fa-file-excel text-success',
            'zip' => 'fas fa-file-archive text-info',
            'rar' => 'fas fa-file-archive text-info',
            'jpg' => 'fas fa-file-image text-success',
            'png' => 'fas fa-file-image text-success',
            'gif' => 'fas fa-file-image text-success'
        );
        
        return isset($icons[$extension]) ? $icons[$extension] : 'fas fa-file text-muted';
    }
}

if (!function_exists('format_file_size')) {
    function format_file_size($bytes) {
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

if (!function_exists('secure_download')) {
    function secure_download($file_path, $download_name = null) {
        $CI =& get_instance();
        
        if (!file_exists($file_path)) {
            show_404();
            return;
        }
        
        // Check file permissions here if needed
        // Example: check if user has access to this file
        
        $CI->load->helper('download');
        
        if ($download_name) {
            force_download($download_name, file_get_contents($file_path));
        } else {
            force_download($file_path, NULL);
        }
    }
}

if (!function_exists('validate_upload_file')) {
    function validate_upload_file($file_data, $allowed_types = 'pdf|doc|docx', $max_size = 10240) {
        $errors = array();
        
        // Check file size (in KB)
        if ($file_data['size'] > $max_size) {
            $errors[] = 'File terlalu besar. Maksimal ' . format_file_size($max_size * 1024);
        }
        
        // Check file type
        $allowed_extensions = explode('|', $allowed_types);
        $file_extension = get_file_extension($file_data['name']);
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = 'Tipe file tidak diizinkan. Hanya: ' . str_replace('|', ', ', $allowed_types);
        }
        
        // Check for dangerous files
        $dangerous_extensions = array('php', 'exe', 'bat', 'com', 'pif', 'scr', 'vbs', 'js');
        if (in_array($file_extension, $dangerous_extensions)) {
            $errors[] = 'Tipe file berbahaya tidak diizinkan';
        }
        
        return empty($errors) ? true : $errors;
    }
}

if (!function_exists('clean_filename')) {
    function clean_filename($filename) {
        // Remove special characters and spaces
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Remove leading/trailing underscores
        $filename = trim($filename, '_');
        
        return $filename;
    }
}

if (!function_exists('get_upload_url')) {
    function get_upload_url($file_path) {
        $CI =& get_instance();
        $base_url = $CI->config->item('base_url');
        
        // Remove leading ./ from file path
        $file_path = ltrim($file_path, './');
        
        return $base_url . $file_path;
    }
}
