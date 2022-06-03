<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

    public function get_messages()
    {
        $this->db->from('messages')->where(['deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return  false;
        }
    }

     public function delete_message($id)
     {
         $this->db->set(['deleted' => 1])->where(['id' => $id])->update('messages');
         if ($this->db->affected_rows() > 0) {
             return true;
         }else {
             return false;
         }
     }

    public function seen_message($id)
    {
        $this->db->set(['seen' => 1])->where(['id' => $id])->update('messages');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;

        }
    }
}