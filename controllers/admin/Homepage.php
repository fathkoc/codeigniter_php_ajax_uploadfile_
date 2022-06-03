<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homepage extends Veripay_Controller
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
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;
        $data->active = "home_page";

        $this->admin_logout();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/index');
        $this->load->view('admin/footer');
    }


    public function list_images_archive()
    {
        $this->admin_logout();
        $post_data_img = new stdClass();
        $admin_info = $this->get_user();
        $data = new stdClass();
        $data->admin_info = $admin_info;

        $data->csrf = [
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        ];

        $post_data_img->image_path = $this->session->userdata('images');
        $this->session->unset_userdata('images');
        if ($this->model->add_archive_images($post_data_img)) {

            redirect(site_url('yonetim-paneli/resim-arsivi'));
        }

        $data->active = "resim_arsivi";
        $data->image_archive = $this->model->get_archive_images();

        $this->load->view('admin/header',$data);
        $this->load->view('admin/image_archive');
        $this->load->view('admin/footer');
    }
}