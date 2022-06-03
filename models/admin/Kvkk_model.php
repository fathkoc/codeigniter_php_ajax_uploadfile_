<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kvkk_model extends CI_Model {
    public function update($post_data)
    {
        $this->db->set($post_data)->update('kvkk_solution');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public  function get_kvkk()
    {
        $this->db->from('kvkk_solution');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }
}