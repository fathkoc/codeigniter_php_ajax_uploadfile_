<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contract extends Veripay_Controller
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
        $data->active = "contracts";

        $data->contract = $this->model->get_contract();

        $this->form_validation->set_rules('title', 'Başlık', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('text', 'Metin', 'required');

        if ($this->form_validation->run() != false) {
            $post_data->title = $this->input->post('title', true);
            $post_data->text = $this->input->post('text', true);
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
        $this->load->view('admin/contract');
        $this->load->view('admin/footer');
    }
}