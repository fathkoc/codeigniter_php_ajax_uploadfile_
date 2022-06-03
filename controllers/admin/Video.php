<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Video extends Veripay_Controller
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
        $data->active = "video";

        $data->video = $this->model->get_video();

        $this->form_validation->set_rules('title', 'Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('sub_title', 'Alt Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('text', 'Metin', 'required|trim|max_length[250]|xss_clean');

        if ($this->form_validation->run() != false) {

            $post_data->title = $this->input->post('title', true);
            $post_data->sub_title = $this->input->post('sub_title', true);
            $post_data->text = $this->input->post('text', true);

            if ($this->session->userdata('video')) {
                $post_data->video_path = $this->session->userdata('video')[0];
                $this->session->unset_userdata('video');
            }

            if ($this->model->update($post_data)) {
                $this->result->status = true;
                $this->response();
            } else {
                $this->result->error = "Güncelleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                $this->response();
            }

        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }
        $this->load->view('admin/header', $data);
        $this->load->view('admin/video');
        $this->load->view('admin/footer');
    }
}