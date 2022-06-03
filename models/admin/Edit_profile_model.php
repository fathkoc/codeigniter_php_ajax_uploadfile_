<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_profile_model extends CI_Model {
    public function admin_info($id)
    {
        $this->db->from('admin');
        $this->db->where(['id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0){
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function update_account_genaral($post_data, $id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('admin');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_account_pass($post_data, $id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('admin');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}