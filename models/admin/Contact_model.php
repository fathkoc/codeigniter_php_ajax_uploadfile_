<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model {

    public function get_contact()
    {
        $this->db->from('contact');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function update($post_data)
    {
        $this->db->set($post_data)->update('contact');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

}