<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_model extends CI_Model {

    public function get_contract()
    {
        $this->db->from('contract');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function update($post_data)
    {
        $this->db->set($post_data)->update('contract');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

}