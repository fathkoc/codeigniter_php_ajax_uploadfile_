<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_model extends CI_Model {

    public function get_slider()
    {
        $this->db->from('slider')->where(['deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_selected_slider($id)
    {
        $this->db->from('slider')->where(['deleted' => 0, 'id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function add($post_data)
    {
        $this->db->set($post_data)->insert('slider');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function update($post_data,$id)
    {
        $this->db->set($post_data)->where(['id' => $id])->update('slider');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function delete($id)
    {
        $this->db->set(['deleted' => 1])->where(['id' => $id])->update('slider');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function status($id,$status)
    {
        $this->db->set(['status' => $status])->where(['id' => $id])->update('slider');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }
}