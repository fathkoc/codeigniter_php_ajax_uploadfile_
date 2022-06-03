<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site_settings extends Veripay_Controller
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
        $data->active = "site_settings";
        $data->site_settings = $this->model->get_site_settings();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/site_settings');
        $this->load->view('admin/footer');
    }

    public function update()
    {
        $post_data = new StdClass();

        $this->form_validation->set_rules('title', 'Site Başlığı', 'required|max_length[250]|xss_clean');
        $this->form_validation->set_rules('description', 'Site Açıklaması', 'required|xss_clean');
        $this->form_validation->set_rules('id', 'İd', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');

        if ($this->form_validation->run() != false) {
            $post_data->title = $this->input->post('title', true);
            $post_data->description = $this->input->post('description',true);
            $id = $this->input->post('id', true);

            if ($this->session->userdata('images')) {
                $post_data->banner_image = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
            }

            if ($this->model->update($post_data,$id)) {
                $this->result->url = site_url('yonetim-paneli/site-ayarlari');
                $this->result->status = true;
            } else {
                $this->result->error = "Güncelleme Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin";
            }

        } else {
            $this->result->error = validation_errors();
        }
        $this->response();
    }

}