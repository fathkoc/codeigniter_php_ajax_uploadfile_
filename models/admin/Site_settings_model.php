<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_settings_model extends CI_Model {
    public function get_site_settings()
    {
        $this->db->from('site_settings');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0){
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function update($post_data,$id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('site_settings');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }
}