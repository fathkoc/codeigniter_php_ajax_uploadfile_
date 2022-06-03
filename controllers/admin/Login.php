<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Veripay_Controller
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
        $this->is_admin_login();
        $this->load->view('admin/login');
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Kullanıcı Adı', 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', 'Şifre', 'required|trim|xss_clean');
        if ($this->form_validation->run() != false) {
            $password = $this->input->post('password', true);
            $username = $this->input->post('username', true);
            if ($this->model->login($password, $username)) {

                if ($this->session->userdata('admin_info')) {
                    if ($this->session->userdata('admin_info')->status == 0) {
                        $this->result->error = "Sisteme Erişim Yetkiniz Yok";
                        $this->session->sess_destroy('admin_info');
                    } else {
                        $this->result->status = true;
                    }
                }
            } else {
                $this->result->error = "Kullanıcı Adı veya Şifre Hatalı";
            }
        } else {
            $this->result->error = validation_errors();
        }
        $this->response();
    }

}