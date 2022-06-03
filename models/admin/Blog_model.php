<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

    public function get_cities()
    {
        $this->db->from('cities');
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return  $return_query->result();
        } else {
            return false;
        }
    }

    public function get_blog($config = [])
    {
        $total_rows       = $this->db->where(['deleted' => 0])->count_all_results('blog');
        $limit_start      = $config['current_page'] > 1 ? ($config['current_page'] - 1) * $config['page'] : 0;
        $this->db->from('blog');
        $this->db->where(['deleted' => 0]);
        $return_query     = $this->db->order_by('id', 'DESC')->limit($config['page'], $limit_start)->get();
        $data             = new stdClass;
        $data->total_rows = $total_rows;
        $data->count      = $return_query->num_rows();
        $data->data       = $return_query->result();
        return $data;
    }

    public function get_selected_blog($id)
    {
        $this->db->from('blog');
        $this->db->where(['deleted' => 0,'id' => $id]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return  $return_query->row();
        } else {
            return false;
        }
    }


    public function add($post_data)
    {

        $this->db->select('id,slug');
        $this->db->from('blog');
        $this->db->where(['slug' => $post_data->slug]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            $this->db->set($post_data)->insert('blog');
            if ($this->db->affected_rows() > 0) {
                $insert_id = $this->db->insert_id();
                $this->db->set(['slug' => $post_data->slug . '-' . $insert_id])->where(['id' => $insert_id])->update('blog');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }else{
            $this->db->set($post_data)->insert('blog');
            if ($this->db->affected_rows() > 0) {
                return true;
            }else {
                return false;
            }
        }
    }

    public function update($post_data,$id)
    {

        $this->db->select('id,slug');
        $this->db->from('blog');
        $this->db->where(['slug' => $post_data->slug]);
        $this->db->where(['id !=' => $id]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            $this->db->set($post_data)->where(['id' => $id])->update('blog');
            if ($this->db->affected_rows() > 0) {
                $this->db->set(['slug' => $post_data->slug . '-' . $id])->where(['id' => $id])->update('blog');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }else{
            $this->db->set($post_data)->where(['id' => $id])->update('blog');
            if ($this->db->affected_rows() > 0) {
                return true;
            }else {
                return false;
            }
        }
    }



    public function delete($id)
    {
        $this->db->set(['deleted' => 1])->where(['id' => $id])->update('blog');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function status($id, $status)
    {
        $this->db->set(['home_page' => $status])->where(['id' => $id])->update('blog');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function home_page($id, $status)
    {
        $this->db->set(['status' => $status])->where(['id' => $id])->update('blog');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_ticket($post_data)
    {
        $this->db->from('tickets');
        $this->db->where(['slug' => $post_data->slug]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() <= 0) {
            $this->db->set(['ticket' => $post_data->ticket ,'slug' => $post_data->slug ])->insert('tickets');
            if ($this->db->affected_rows() > 0) {
                $ticket_id = $this->db->insert_id();
                $this->db->from('ticket_match_blog');
                $this->db->where(['ticket_id' => $ticket_id, 'deleted' => 0, 'blog_id' => $post_data->blog_id]);
                $return_query_ = $this->db->get();
                if ($return_query_->num_rows() <= 0) {
                    $this->db->set(['ticket_id' => $ticket_id, 'blog_id' => $post_data->blog_id ])->insert('ticket_match_blog');
                    if ($this->db->affected_rows() > 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }else{
            $this->db->from('ticket_match_blog');
            $this->db->where(['ticket_id' => $return_query->row()->id, 'deleted' => 0, 'blog_id' => $post_data->blog_id]);
            $return_query_ = $this->db->get();
            if ($return_query_->num_rows() <= 0) {
                $this->db->set(['ticket_id' => $return_query->row()->id, 'blog_id' => $post_data->blog_id])->insert('ticket_match_blog');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else{
                return false;
            }
        }
    }

    public function delete_ticket($id)
    {
        $this->db->set(['deleted' => 1])->where(['id' => $id])->update('ticket_match_blog');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_selected_blog_tickets($id)
    {
        $this->db->select('tb.*, tb.id as tb_id, t.*, t.id as t_id');
        $this->db->from('ticket_match_blog tb');
        $this->db->join('tickets t', 'tb.ticket_id = t.id','left');
        $this->db->where(['tb.blog_id' => $id ,'tb.deleted' => 0]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            return $return_query->result();
        } else {
            return false;
        }
    }

    public function get_categories()
    {
        $this->db->from('blog_categories')->where(['deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return  $return_query->result();
        } else {
            return false;
        }
    }

    public function get_selected_category($id)
    {
        $this->db->from('blog_categories')->where(['id' => $id,'deleted' => 0]);
        $return_query = $this->db->get();
        if($return_query->num_rows() > 0) {
            return  $return_query->row();
        } else {
            return false;
        }
    }


    public function add_category($post_data)
    {
        $this->db->select('id,slug');
        $this->db->from('blog_categories');
        $this->db->where(['slug' => $post_data->slug]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            $this->db->set($post_data)->insert('blog_categories');
            if ($this->db->affected_rows() > 0) {
                $insert_id = $this->db->insert_id();
                $this->db->set(['slug' => $post_data->slug . '-' . $insert_id])->where(['id' => $insert_id])->update('blog_categories');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }else{
            $this->db->set($post_data)->insert('blog_categories');
            if ($this->db->affected_rows() > 0) {
                return true;
            }else {
                return false;
            }
        }
    }

    public function update_category($post_data,$id)
    {

        $this->db->select('id,slug');
        $this->db->from('blog_categories');
        $this->db->where(['slug' => $post_data->slug]);
        $this->db->where(['id !=' => $id]);
        $return_query = $this->db->get();
        if ($return_query->num_rows() > 0) {
            $this->db->set($post_data)->where(['id' => $id])->update('blog_categories');
            if ($this->db->affected_rows() > 0) {
                $this->db->set(['slug' => $post_data->slug . '-' . $id])->where(['id' => $id])->update('blog_categories');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }else{
            $this->db->set($post_data)->where(['id' => $id])->update('blog_categories');
            if ($this->db->affected_rows() > 0) {
                return true;
            }else {
                return false;
            }
        }
    }

    public function delete_category($id)
    {
        $this->db->set(['deleted' => 1])->where(['id' => $id])->update('blog_categories');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function status_category($id, $status)
    {
        $this->db->set(['status' => $status])->where(['id' => $id])->update('blog_categories');
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}