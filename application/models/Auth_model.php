<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function login($nisn_nip, $password) {
        $this->db->where('nisn_nip', $nisn_nip);
        $this->db->where('is_active', 1);
        $user = $this->db->get('users')->row();
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return FALSE;
    }
    
    public function change_password($user_id, $new_password) {
        $data = array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
            'force_change_password' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
    
    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->get('users')->row();
    }
}