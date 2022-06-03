<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage_model extends CI_Model {


    public function get_slider()
    {
        $this->db->from('slider')->where(['deleted' => 0, 'status' => 1]);

        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_about()
    {
        $this->db->from('about');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_e_commerce()
    {
        $this->db->from('e_commerce_solution');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_services()
    {
        $this->db->from('services');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_video()
    {
        $this->db->from('video');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_kvkk()
    {
        $this->db->from('kvkk_solution');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public  function get_contact()
    {
        $this->db->from('contact');
        $return_query = $this->db->order_by('id' ,'DESC')->get();
        if($return_query->num_rows() > 0) {
            return $return_query->row();
        } else {
            return false;
        }
    }

    public function get_banner()
    {
        $this->db->from('banner');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

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

    public function get_references()
    {
        $this->db->from('references')->where(['status' => 1, 'deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_homepage_blog()
    {
        $this->db->from('blog')->where(['status' => 1, 'deleted' => 0, 'home_page' => 1]);
        $return_query = $this->db->limit(3)->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_site_settings($id)
    {
        $this->db->select('title,description,banner_image');
        $this->db->from('site_settings')->where(['id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return  $return_query->row();
        } else {
            return false;
        }
    }


    public function get_selected_blog_tickets($id = '')
    {
        $this->db->select('tb.*, tb.id as tb_id, t.*, t.id as t_id');
        $this->db->from('ticket_match_blog tb');
        $this->db->join('tickets t', 'tb.ticket_id = t.id','left');
        $this->db->where(['tb.deleted' => 0, 'tb.status' => 1]);
        if($id) {
            $this->db->where(['tb.blog_id' => $id ]);
        }
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_ticket_blog($config = [],$slug)
    {
        $this->db->select('b.*, tb.id as tb_id, t.id as t_id');
        $this->db->from('ticket_match_blog tb');
        $this->db->join('tickets t', 'tb.ticket_id = t.id','left');
        $this->db->join('blog b', 'tb.blog_id = b.id');
        $this->db->where(['t.slug' => $slug ,'tb.deleted' => 0, 'tb.status' => 1,'b.deleted' => 0, 'b.status' => 1]);
        $total_rows       = $this->db->count_all_results();
        $limit_start      = $config['current_page'] > 1 ? ($config['current_page'] - 1) * $config['page'] : 0;
        $this->db->select('b.*, tb.id as tb_id, t.id as t_id');
        $this->db->from('ticket_match_blog tb');
        $this->db->join('tickets t', 'tb.ticket_id = t.id','left');
        $this->db->join('blog b', 'tb.blog_id = b.id');
        $this->db->where(['t.slug' => $slug ,'tb.deleted' => 0, 'tb.status' => 1,'b.deleted' => 0, 'b.status' => 1]);
        $return_query     = $this->db->order_by('b.id', 'DESC')->limit($config['page'], $limit_start)->get();
        $data             = new stdClass;
        $data->total_rows = $total_rows;
        $data->count      = $return_query->num_rows();
        $data->data       = $return_query->result();
        return $data;
    }

    public function get_categories()
    {
        $this->db->from('blog_categories');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_category_blog($config = [],$slug)
    {
        $this->db->select('b.*, c.id as c_id');
        $this->db->from('blog_categories c');
        $this->db->join('blog b', 'c.id = b.category_id');
        $this->db->where(['c.slug' => $slug ,'c.deleted' => 0, 'c.status' => 1,'b.deleted' => 0, 'b.status' => 1]);
        $total_rows       = $this->db->count_all_results();
        $limit_start      = $config['current_page'] > 1 ? ($config['current_page'] - 1) * $config['page'] : 0;
        $this->db->select('b.*, c.id as c_id');
        $this->db->from('blog_categories c');
        $this->db->join('blog b', 'c.id = b.category_id');
        $this->db->where(['c.slug' => $slug ,'c.deleted' => 0, 'c.status' => 1,'b.deleted' => 0, 'b.status' => 1]);
        $return_query     = $this->db->order_by('b.id', 'DESC')->limit($config['page'], $limit_start)->get();
        $data             = new stdClass;
        $data->total_rows = $total_rows;
        $data->count      = $return_query->num_rows();
        $data->data       = $return_query->result();
        return $data;
    }

    public function get_blog($config = [],$post_data)
    {

        if(empty($post_data->search_word)) {
            $total_rows = $this->db->where(['deleted' => 0, 'status' => 1])->count_all_results('blog');
        } else {
            $total_rows = $this->db->where(['deleted' => 0, 'status' => 1])->like('title', $post_data->search_word)->count_all_results('blog');
        }

        $limit_start      = $config['current_page'] > 1 ? ($config['current_page'] - 1) * $config['page'] : 0;
        $this->db->from('blog');
        $this->db->where(['status' => 1,'deleted' => 0]);
        if(!empty($post_data->search_word)) {
            $this->db->like('title', $post_data->search_word);
        }
        $return_query     = $this->db->order_by('id', 'DESC')->limit($config['page'], $limit_start)->get();
        $data             = new stdClass;
        $data->total_rows = $total_rows;
        $data->count      = $return_query->num_rows();
        $data->data       = $return_query->result();
        return $data;
    }

    public function get_selected_blog($slug)
    {

        $this->db->from('blog');
        $this->db->where(['slug' => $slug ,'deleted' => 0, 'status' => 1]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0){
            return $return_query->row();
        }
        else{
            return false;
        }
    }
}