<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_model extends CI_Model {
    public function get_contract()
    {
        $this->db->from('contract');
        $result_query = $this->db->get();
        if($result_query->num_rows() > 0) {
            return $result_query->row();
        } else {
            return  false;
        }
    }

    public function add_message($post_data)
    {
        $this->db->set($post_data)->insert('messages');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function add_request($post_data)
    {
        $this->db->set($post_data)->insert('request');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

}