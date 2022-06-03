<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends Veripay_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');
        $this->result = new StdClass();
        $this->result->status = false;
        $this->load->model('admin/' . $this->router->fetch_class() . '_model', 'model');
    }

    public function response()
    {
        echo json_encode($this->result);
        exit();
    }

    public function get_counties()
    {
        $this->form_validation->set_rules('city_id', 'İd', 'required|numeric|min_length[1]|max_length[11]|trim|xss_clean');
        if ($this->form_validation->run() != false) {
            $city_id = $this->input->post('city_id', true);
            $data = new  stdClass();
            if (!$data->counties = $this->model->get_counties($city_id)) {
                $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
                $this->response();
            }

            $this->result->counties = $data->counties;
            $this->result->status = true;
            $this->response();
        } else {
            $this->result->error = validation_errors();
            $this->response();
        }
    }

    public function remove_images()
    {
        $this->session->unset_userdata('images');
        $this->session->unset_userdata('cover_images');
        $this->session->unset_userdata('images2');
        $this->session->unset_userdata('video');
        $this->result->status = true;
        $this->response();
    }

    public function delete_archive_image()
    {
        $id = $this->input->post('id', true);
        if ($this->model->delete_archive_image($id)) {
            $this->result->status = true;
        } else {
            $this->result->error = "İşlem Başarısız Tekrar Deneyin.";
        }
        $this->response();
    }



}
