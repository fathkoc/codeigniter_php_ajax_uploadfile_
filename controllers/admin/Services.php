<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Services extends Veripay_Controller
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
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "services";

        $data->services = $this->model->get_services();

        $this->form_validation->set_rules('title', 'Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('text', 'Metin', 'required');
        $this->form_validation->set_rules('site_title', 'Site Başlığı', 'required|trim|max_length[65]|xss_clean');
        $this->form_validation->set_rules('site_description', 'Site Açıklaması', 'required|trim|max_length[160]|xss_clean');

        if ($this->form_validation->run() != false) {

            $post_data->title = $this->input->post('title', true);
            $post_data->text = $this->input->post('text', false);
            $post_data->site_title = $this->input->post('site_title', true);
            $post_data->site_description = $this->input->post('site_description', true);

            if ($this->session->userdata('images')) {
                $post_data->image_path = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
            }

            if ($this->session->userdata('images2')) {
                $post_data->banner_image = $this->session->userdata('images2')[0];
                $this->session->unset_userdata('images2');
            }
            if ($this->model->update($post_data)) {
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
        $this->load->view('admin/services');
        $this->load->view('admin/footer');
    }
}