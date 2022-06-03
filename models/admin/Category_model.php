<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function category_list()
    {
        $this->db->from('categories');
        $this->db->where(['deleted' => 0 , 'top_category_id' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function selected_category_list()
    {
        $this->db->from('categories');
        $this->db->where(['deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function category_sub_list($top_category_id)
    {
        $this->db->from('categories');
        $this->db->where(['deleted' => 0, 'top_category_id' => $top_category_id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function previus_category_id($top_category_id)
    {
        $this->db->from('categories');
        $this->db->where(['deleted' => 0, 'id' => $top_category_id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_selected_category($id)
    {
        $this->db->from('categories');
        $this->db->where(['id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows()) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function add_category($post_data)
    {
        $this->db->from('categories');
        $this->db->where(['slug' => $post_data->slug, 'top_category_id' => $post_data->top_category_id]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            return 5;
        } else {
            $this->db->set($post_data)->insert('categories');
            if($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function update_category(object $post_data, int $id)
    {
        $this->db->from('categories');
        $this->db->where(['id' => $id]);
        $return_query = $this->db->get();
        $top_category_id = $return_query->row()->top_category_id;

        $this->db->from('categories');
        $this->db->where(['id !=' => $id,'slug' => $post_data->slug, 'top_category_id' => $top_category_id]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            return false;
        } else {
            return $this->db->set($post_data)->where(['id' => $id])->update('categories');
        }
    }

     public function delete($id)
     {
         $this->db->set(['deleted' => 1])->where(['id' => $id])->update('categories');
         if ($this->db->affected_rows() > 0) {
             return true;
         }else {
             return false;
         }
     }

     public function status($id,$status)
     {
         $this->db->set(['status' => $status])->where(['id' => $id])->update('categories');
         if ($this->db->affected_rows() > 0) {
             return true;
         }else {
             return false;
         }
     }
}