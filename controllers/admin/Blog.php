<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends Veripay_Controller
{

    function __construct()
    {
        parent:: __construct();

        $this->result = new StdClass();
        $this->result->status = false;
        $this->load->model('admin/' . $this->router->fetch_class() . '_model', 'model');
    }

    public function response()
    {
        echo json_encode($this->result);
        exit();
    }

    public function index($page = '')
    {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog";

        $pagination_config = new stdClass();
        $this->load->library('pagination');

        if (empty($page)) {
            $data->current_page = 1;
        } else {
            $data->current_page = $page;
        }
        $pagination_model_config = ['page' =>10, 'current_page' => $data->current_page];

        $data->list = $this->model->get_blog($pagination_model_config);

        $pagination_config->url    		= site_url('yonetim-paneli/blog-listele');

        $pagination_config->per_page   		= $pagination_model_config['page'];
        $pagination_config->total_rows 	= $data->list->total_rows;
        $this->pagination->initialize(create_pagination_config($pagination_config));
        $data->pagination_text = $this->pagination->create_links();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog/list');
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $post_data_img = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog";
        $data->categories = $this->model->get_categories();
        $this->form_validation->set_rules('title', 'Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('cover_text', 'Kapak Metni', 'required|trim|max_length[600]|xss_clean');
        $this->form_validation->set_rules('text', 'Blog Metni', 'required|trim');
        $this->form_validation->set_rules('site_title', 'Site Başlığı', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('site_description', 'Site Açıklaması', 'required|trim|xss_clean');

        if ($this->form_validation->run() != false) {
            $post_data->category_id = $this->input->post('category', true);
            $post_data->title = $this->input->post('title', true);
            $this->form_validation->set_rules('author', 'Yazar', 'required|trim|max_length[250]|xss_clean');
            $post_data->author = $this->input->post('author', true);
            $post_data->cover_text = $this->input->post('cover_text', true);
            $post_data->text = $this->input->post('text', false);
            $post_data->site_title = $this->input->post('site_title', true);
            $post_data->site_description = $this->input->post('site_description', true);
            $post_data->slug = url_seo($post_data->title);
            $post_data->date = date('Y-m-d');

            if ($this->session->userdata('cover_images') && $this->session->userdata('images') && $this->session->userdata('images2')) {

                $post_data->image_path = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
                $post_data->cover_image = $this->session->userdata('cover_images')[0];
                $this->session->unset_userdata('cover_images');
                $post_data->banner_image = $this->session->userdata('images2')[0];
                $this->session->unset_userdata('images2');


                if ($this->model->add($post_data,$post_data_img)) {
                    $this->result->url = site_url('yonetim-paneli/blog-listele');
                    $this->result->status = true;
                    $this->response();
                } else {
                    $this->result->error = "Ekleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                    $this->response();
                }
            } else {

                $this->result->error = "Lütfen Resim Alanlarını Boş Bırakmayınız.";
                $this->response();
            }
        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog/add');
        $this->load->view('admin/footer');

    }

    public function update($id = '')
    {
        if(!empty($this->input->post('id', true))) {
            $id = $this->input->post('id', true);
        }
        if(empty($id)) {
            redirect(site_url('yonetim-paneli/blog-listele'));
        }
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog";
        $data->categories = $this->model->get_categories();
        $data->blog = $this->model->get_selected_blog($id);

        $this->form_validation->set_rules('category', 'Kategori', 'required|trim|max_length[11]|xss_clean|integer');
        $this->form_validation->set_rules('title', 'Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('author', 'Yazar', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('cover_text', 'Kapak Metni', 'required|trim|max_length[600]|xss_clean');
        $this->form_validation->set_rules('text', 'Blog Metni', 'required|trim');
        $this->form_validation->set_rules('site_title', 'Site Başlığı', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('site_description', 'Site Açıklaması', 'required|trim|xss_clean');
        $this->form_validation->set_rules('id', 'id', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');
        if ($this->form_validation->run() != false) {
            $post_data->category_id = $this->input->post('category', true);
            $post_data->title = $this->input->post('title', true);
            $post_data->author = $this->input->post('author', true);
            $post_data->cover_text = $this->input->post('cover_text', true);
            $post_data->text = $this->input->post('text', false);
            $post_data->site_title = $this->input->post('site_title', true);
            $post_data->site_description = $this->input->post('site_description', true);
            $post_data->slug = url_seo($post_data->title);
            $post_data->date = date('Y-m-d');
            $id = $this->input->post('id', true);

            if ($this->session->userdata('images')) {
                $post_data->image_path = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
            }
            if ($this->session->userdata('cover_images')) {
                $post_data->cover_image = $this->session->userdata('cover_images')[0];
                $this->session->unset_userdata('cover_images');
            }
            if ($this->session->userdata('images2')) {
                $post_data->banner_image = $this->session->userdata('images2')[0];
                $this->session->unset_userdata('images2');
            }

            if ($this->model->update($post_data,$id)) {
                $this->result->url = site_url('yonetim-paneli/blog-listele');
                $this->result->status = true;
                $this->response();
            } else {
                $this->result->error = "Ekleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                $this->response();
            }

        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }
        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog/update');
        $this->load->view('admin/footer');
    }

    public function status()
    {
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);
        if ($this->model->status($id, $status)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

    public function delete()
    {
        $id = $this->input->post('id', true);
        if ($this->model->delete($id)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

    public function home_page()
    {
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);
        if ($this->model->home_page($id, $status)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }


    public function add_ticket($id = '')
    {
        $this->admin_logout();
        if(!empty($this->input->post('id', true))) {
            $id = $this->input->post('id', true);
        }
        if(empty($id)) {
            redirect(site_url('yonetim-paneli/blog-listele'));
        }
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog";;
        $data->id = $id;

        $data->tickets = $this->model->get_selected_blog_tickets($id);

        $post_data = new stdClass();
        $this->form_validation->set_rules('name', 'Etiket Adı', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('id', 'İd', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');
        if($this->form_validation->run() != false) {
            $post_data->ticket = $this->input->post('name');
            $post_data->blog_id = $this->input->post('id');
            $post_data->slug = url_seo($post_data->ticket);
            if($this->model->add_ticket($post_data)) {
                $this->result->status = true;
                $this->response();
            } else {
                $this->result->error = "Ekleme İşlemi Başarısız Lütfen Tekrar Deneyin";
                $this->response();
            }
        }else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }
        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog/tickets');
        $this->load->view('admin/footer');
    }
    public function delete_ticket()
    {
        $id = $this->input->post('id', true);
        if ($this->model->delete_ticket($id)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

    public function list_category()
    {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog_category";

        $data->category = $this->model->get_categories();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog_category/list');
        $this->load->view('admin/footer');
    }

    public function add_category()
    {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog_category";

        $this->form_validation->set_rules('name', 'Kategori Adı', 'required|trim|max_length[250]|xss_clean');

        if ($this->form_validation->run() != false) {
            $post_data->name = $this->input->post('name', true);
            $post_data->slug = url_seo($post_data->name);

            if ($this->model->add_category($post_data)) {
                $this->result->url = site_url('yonetim-paneli/blog-kategori-listele');
                $this->result->status = true;
                $this->response();
            } else {
                $this->result->error = "Ekleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                $this->response();
            }
        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog_category/add');
        $this->load->view('admin/footer');
    }

    public function update_category($id = '')
    {
        if(!empty($this->input->post('id', true))) {
            $id = $this->input->post('id', true);
        }
        if(empty($id)) {
            redirect(site_url('yonetim-paneli/blog-listele'));
        }
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "blog_category";

        $data->category = $this->model->get_selected_category($id);

        $this->form_validation->set_rules('name', 'Kategori Adı', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('id', 'id', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');

        if ($this->form_validation->run() != false) {
            $post_data->name = $this->input->post('name', true);
            $post_data->slug = url_seo($post_data->name);
            $id = $this->input->post('id', true);
            if ($this->model->update_category($post_data,$id)) {
                $this->result->url = site_url('yonetim-paneli/blog-kategori-listele');
                $this->result->status = true;
                $this->response();
            } else {
                $this->result->error = "Ekleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                $this->response();
            }
        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/blog_category/update');
        $this->load->view('admin/footer');
    }

    public function status_category()
    {
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);
        if ($this->model->status_category($id, $status)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

    public function delete_category()
    {
        $id = $this->input->post('id', true);
        if ($this->model->delete_category($id)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

}