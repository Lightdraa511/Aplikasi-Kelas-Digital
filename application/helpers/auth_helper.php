<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('user_id') ? TRUE : FALSE;
    }
}

if (!function_exists('get_user_data')) {
    function get_user_data($key = null) {
        $CI =& get_instance();
        if ($key) {
            return $CI->session->userdata($key);
        }
        return array(
            'user_id' => $CI->session->userdata('user_id'),
            'nisn_nip' => $CI->session->userdata('nisn_nip'),
            'nama_lengkap' => $CI->session->userdata('nama_lengkap'),
            'role' => $CI->session->userdata('role'),
            'force_change_password' => $CI->session->userdata('force_change_password')
        );
    }
}

if (!function_exists('check_role')) {
    function check_role($allowed_roles) {
        if (!is_array($allowed_roles)) {
            $allowed_roles = array($allowed_roles);
        }
        return in_array(get_user_data('role'), $allowed_roles);
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            $CI =& get_instance();
            $CI->session->set_flashdata('error', 'Silakan login terlebih dahulu');
            redirect('login');
        }
    }
}

if (!function_exists('require_role')) {
    function require_role($allowed_roles) {
        require_login();
        if (!check_role($allowed_roles)) {
            $CI =& get_instance();
            $CI->session->set_flashdata('error', 'Akses ditolak!');
            redirect('dashboard');
        }
    }
}

if (!function_exists('generate_password')) {
    function generate_password($tahun_ajaran, $nisn_nip, $role) {
        if ($role === 'super_admin') {
            return $tahun_ajaran . 'admin';
        }
        return $tahun_ajaran . $nisn_nip;
    }
}