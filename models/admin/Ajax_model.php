<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax_model extends CI_Model
{
    public function get_counties($city_id)
    {
        $this->db->from('counties')->where(['city_id' => $city_id]);
        $result_query = $this->db->get();
        if($result_query->num_rows() > 0) {
            return $result_query->result();
        } else {
            return  false;
        }
    }

    public function delete_archive_image($id)
    {
        $this->db->set(['deleted' => 1])->where(['id' => $id])->update('image_archive');
        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }


}