<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class What_we_model extends CI_Model {

    public function get_what_we()
    {
        $this->db->from('what_we');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_selected_what_we($id)
    {
        $this->db->from('what_we')->where(['id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function update($post_data,$id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('what_we');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

}