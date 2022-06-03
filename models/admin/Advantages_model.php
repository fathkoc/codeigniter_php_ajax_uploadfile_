<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advantages_model extends CI_Model {

    public function get_advantages()
    {
        $this->db->from('advantages');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_selected_advantages($id)
    {
        $this->db->from('advantages')->where(['id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function update($post_data,$id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('advantages');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

}