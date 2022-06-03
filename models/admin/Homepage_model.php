<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage_model extends CI_Model {

    public function get_archive_images()
    {
        $this->db->from('image_archive');
        $this->db->where(['deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function add_archive_images($post_data_img)
    {
        if (!empty($post_data_img->image_path)) {
            foreach ($post_data_img->image_path as $key => $value) {

                $this->db->set(['image_path' => $value])->insert('image_archive');
            }
            if($this->db->affected_rows() > 0){
                return true;
            } else {
                return false;
            }
        }
    }

}