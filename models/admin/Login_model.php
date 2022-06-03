<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function login($password, $username)
    {
        $this->db->from("admin");
        $this->db->where(['user_name' => $username]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {

            $admininfo = $return_query->row();
            if (password_verify($password, $admininfo->password)) {
                $this->session->set_userdata('admin_info', $admininfo);
                return true;
            } else {
                return false;
            }
        }
    }
}