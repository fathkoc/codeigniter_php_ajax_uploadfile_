<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends Veripay_Controller
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

   public function index()
   {
       $this->admin_logout();
       $admin_info = $this->get_user();
       $data = new stdClass();
       $data->admin_info = $admin_info;
       $data->active = "banner";

       $data->banner = $this->model->get_banner();

       $this->load->view('admin/header', $data);
       $this->load->view('admin/banners/list');
       $this->load->view('admin/footer');
   }

    public function update($id = '')
    {
        if(!empty($this->input->post('id', true))) {
            $id = $this->input->post('id', true);
        }
        if(empty($id)) {
            redirect(site_url('yonetim-paneli/banner-listele'));
        }
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "banner";

        $data->banner = $this->model->get_selected_banner($id);

        $this->form_validation->set_rules('title', 'Başlık', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('text', 'Metin', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('link', 'Link', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('id', 'id', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');
        if ($this->form_validation->run() != false) {
            $post_data->link = $this->input->post('link', true);
            $post_data->title = $this->input->post('title', true);
            $post_data->text = $this->input->post('text', true);
            $id = $this->input->post('id', true);

            if ($this->session->userdata('icon')) {
                $post_data->image_path = $this->session->userdata('icon')[0];
                $this->session->unset_userdata('icon');
            }
            if ($this->model->update($post_data,$id)) {
                $this->result->url = site_url('yonetim-paneli/banner-listele');
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
        $this->load->view('admin/banners/update');
        $this->load->view('admin/footer');
    }

}