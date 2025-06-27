<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all users with pagination and filter
    public function get_users($limit = 10, $offset = 0, $search = '', $role_filter = '') {
        $this->db->select('id, nisn_nip, nama_lengkap, role, is_active, created_at');
        $this->db->from('users');
        
        // Search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nisn_nip', $search);
            $this->db->group_end();
        }
        
        // Role filter
        if (!empty($role_filter)) {
            $this->db->where('role', $role_filter);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    // Count users for pagination
    public function count_users($search = '', $role_filter = '') {
        $this->db->from('users');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nisn_nip', $search);
            $this->db->group_end();
        }
        
        if (!empty($role_filter)) {
            $this->db->where('role', $role_filter);
        }
        
        return $this->db->count_all_results();
    }
    
    // Get user by ID
    public function get_user($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row();
    }
    
    // Check if NISN/NIP already exists
    public function check_nisn_nip_exists($nisn_nip, $exclude_id = null) {
        $this->db->where('nisn_nip', $nisn_nip);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }
    
    // Create new user
    public function create_user($data) {
        // Generate default password
        $tahun_ajaran = date('Y'); // Atau bisa diambil dari periode aktif
        $default_password = generate_password($tahun_ajaran, $data['nisn_nip'], $data['role']);
        
        $user_data = array(
            'nisn_nip' => $data['nisn_nip'],
            'nama_lengkap' => $data['nama_lengkap'],
            'password' => password_hash($default_password, PASSWORD_DEFAULT),
            'role' => $data['role'],
            'is_active' => 1,
            'force_change_password' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->insert('users', $user_data)) {
            return array(
                'success' => true,
                'user_id' => $this->db->insert_id(),
                'default_password' => $default_password
            );
        }
        
        return array('success' => false);
    }
    
    // Update user
    public function update_user($id, $data) {
        $user_data = array(
            'nisn_nip' => $data['nisn_nip'],
            'nama_lengkap' => $data['nama_lengkap'],
            'role' => $data['role'],
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        return $this->db->update('users', $user_data);
    }
    
    // Delete user (soft delete by setting inactive)
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->update('users', array(
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    // Toggle user status (active/inactive)
    public function toggle_status($id) {
        $user = $this->get_user($id);
        if ($user) {
            $new_status = $user->is_active ? 0 : 1;
            $this->db->where('id', $id);
            return $this->db->update('users', array(
                'is_active' => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        return false;
    }
    
    // Reset password to default
    public function reset_password($id) {
        $user = $this->get_user($id);
        if ($user) {
            $tahun_ajaran = date('Y');
            $default_password = generate_password($tahun_ajaran, $user->nisn_nip, $user->role);
            
            $this->db->where('id', $id);
            $result = $this->db->update('users', array(
                'password' => password_hash($default_password, PASSWORD_DEFAULT),
                'force_change_password' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                return array(
                    'success' => true,
                    'default_password' => $default_password
                );
            }
        }
        
        return array('success' => false);
    }
    
    // Get user statistics
    public function get_user_stats() {
        $stats = array();
        
        $this->db->select('role, COUNT(*) as total');
        $this->db->from('users');
        $this->db->where('is_active', 1);
        $this->db->group_by('role');
        $result = $this->db->get()->result();
        
        foreach ($result as $row) {
            $stats[$row->role] = $row->total;
        }
        
        return $stats;
    }
}