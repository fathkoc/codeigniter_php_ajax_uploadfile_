<?php
defined('BASEPATH') or exit('No direct script access allowed');

class References extends Veripay_Controller
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
       $data->active = "references";

       $data->references = $this->model->get_references();

       $this->load->view('admin/header', $data);
       $this->load->view('admin/references/list');
       $this->load->view('admin/footer');
   }

    public function add()
    {
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "references";

        $this->form_validation->set_rules('title', 'Başlık', 'trim|max_length[250]|xss_clean');

        if ($this->form_validation->run() != false) {
            $post_data->title = $this->input->post('title', true);

            if ($this->session->userdata('images')) {

                $post_data->image_path = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');

                if ($this->model->add($post_data)) {
                    $this->result->url = site_url('yonetim-paneli/referans-listele');
                    $this->result->status = true;
                    $this->response();
                } else {
                    $this->result->error = "Ekleme İşlemi Esnasında Bir Hata Oluştu Lütfen Tekrar Deneyin.";
                    $this->response();
                }
            } else {

                $this->result->error = "Lütfen Bir Resim Ekleyin.";
                $this->response();
            }
        } else {
            $this->result->error = validation_errors();
            if(!empty($this->result->error)) {
                $this->response();
            }
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/references/add');
        $this->load->view('admin/footer');
    }

    public function update($id = '')
    {
        if(!empty($this->input->post('id', true))) {
            $id = $this->input->post('id', true);
        }
        if(empty($id)) {
            redirect(site_url('yonetim-paneli/refereans-listele'));
        }
        $this->admin_logout();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $post_data = new StdClass();
        $data->admin_info = $admin_info;
        $data->active = "references";

        $data->references = $this->model->get_selected_references($id);

        $this->form_validation->set_rules('title', 'Başlık', 'trim|max_length[250]|xss_clean');
        $this->form_validation->set_rules('id', 'id', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');
        if ($this->form_validation->run() != false) {
            $post_data->title = $this->input->post('title', true);
            $id = $this->input->post('id', true);

            if ($this->session->userdata('images')) {
                $post_data->image_path = $this->session->userdata('images')[0];
                $this->session->unset_userdata('images');
            }
            if ($this->model->update($post_data,$id)) {
                $this->result->url = site_url('yonetim-paneli/referans-listele');
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
        $this->load->view('admin/references/update');
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
}