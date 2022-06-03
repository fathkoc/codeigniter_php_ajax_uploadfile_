<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Veripay_Controller
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
        $data->active = "contact";
        $data->contact = $this->model->get_contact();

        $this->form_validation->set_rules('phone', 'Telefon', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('e_mail', 'Mail', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('address', 'Adres', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('facebook', 'Facebook', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('twitter', 'Twitter', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('instagram', 'Twitter', 'trim|max_length[250]|xss_clean');

        if ($this->form_validation->run() != false) {

            $post_data->phone = $this->input->post('phone', true);
            $post_data->e_mail = $this->input->post('e_mail', true);
            $post_data->address = $this->input->post('address', true);
            $post_data->facebook = $this->input->post('facebook', true);
            $post_data->linkedin = $this->input->post('linkedin', true);
            $post_data->twitter = $this->input->post('twitter', true);
            $post_data->instagram = $this->input->post('instagram', true);

            if ($this->model->update($post_data)) {
                $this->result->url = site_url('yonetim-paneli/iletisim');
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
        $this->load->view('admin/contact');
        $this->load->view('admin/footer');
    }

}