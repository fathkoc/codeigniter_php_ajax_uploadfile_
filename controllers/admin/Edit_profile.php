<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edit_profile extends Veripay_Controller
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

    public function index() {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "edit_profile";
        $this->load->view('admin/header', $data);
        $this->load->view('admin/edit_profile');
        $this->load->view('admin/footer');
    }

    public function update_account_genaral()
    {
        $post_data = new stdClass();
        $id = $this->session->userdata('admin_info')->id;
        $this->form_validation->set_rules('user_name', 'Kullanıcı Adı', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('name_surname', 'Ad Soyad', 'required|trim|max_length[250]|xss_clean');
        if ($this->form_validation->run() != false) {
            $post_data->name_surname = $this->input->post('name_surname', true);
            $post_data->user_name = $this->input->post('user_name', true);

            if($this->session->userdata('images')) {
                $post_data->profil_photo = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
            }

            if ($this->model->update_account_genaral($post_data,$id)) {
                $this->result->status = true;
            } else {
                $this->result->error = "Güncelleme İşlemi Başarısız Tekrar Denneyin";
            }
        } else {
            $this->result->error = validation_errors();
        }
        $this->response();
    }

    public function update_pass_account()
    {
        $admin_info = $this->get_user();
        $post_data = new stdClass();
        $id = $admin_info->id;
        $this->form_validation->set_rules('old_pass', 'Eski Şifre', 'required|trim|max_length[250]');
        $this->form_validation->set_rules('password', 'Yeni Şifre', 'required|trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('confirm-password', 'Yeni Şİfre Tekrar', 'required|trim|max_length[250]|xss_clean|matches[password]');

        if ($this->form_validation->run() != false) {

            $old_pass = $this->input->post('old_pass');
            if(password_verify($old_pass,$admin_info->password)) {
                $post_data->password = password_hash($this->input->post('password', true),PASSWORD_DEFAULT);

                if ($this->model->update_account_pass($post_data,$id)) {
                    $this->result->status = true;
                } else {
                    $this->result->error = "Güncelleme İşlemi Başarısız Tekrar Denneyin";
                }

            } else {
                $this->result->error = "Eski Şifrenizi Hatalı Giridiniz";
            }

        } else {
            $this->result->error = validation_errors();
        }
        $this->response();
    }
}