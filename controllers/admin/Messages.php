<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Messages extends Veripay_Controller
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
        $data->active = "messages";
        $data->messages = $this->model->get_messages();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/messages');
        $this->load->view('admin/footer');
    }


    public function delete_message()
    {
        $id = $this->input->post('id', true);
        if ($this->model->delete_message($id)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }

    public function seen_message()
    {
        $id = $this->input->post('id', true);

        if ($this->model->seen_message($id)) {
            $this->result->status = true;
        }
        $this->response();
    }
}